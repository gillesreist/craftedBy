<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Address;
use App\Models\Sku;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Order::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        $total_price = 0;

        // * for each product in the order we calcultate the total price
        foreach ($validatedData['skus'] as $skuData) {

            $sku = Sku::find($skuData['id']);
            $total_price += $sku->unit_price * $skuData['quantity'];
        }

        // * prepare the order with the total price in the order_price
        $orderData = [
            'user_id' => Auth::id(),
            'status' => OrderStatusEnum::WAITINGFORPAYMENT,
            'date' => now(),
            'price' => $total_price,
        ];

        // * create addresses string

        $delivery_address = Address::find($validatedData['delivery_address']);
        $orderData['delivery_address'] = $this->formatAddress($delivery_address);

        // * if there is a facturation address
        if (isset($validatedData['facturation_address'])) {
            $facturation_address = Address::find($validatedData['facturation_address']);
            $orderData['facturation_address'] = $this->formatAddress($facturation_address);
        }

        // * create the order
        $order = Order::create($orderData);

        $tax = Tax::where('name', 'consequatur')->first();
        // * Attach skus to orders in the orders_sku table
        foreach ($validatedData['skus'] as $skuData) {
            $sku = Sku::find($skuData['id']);

            $order->skus()->attach($skuData['id'], [
                'tax_id' => $tax->id,
                'sku_name' => $sku->name,
                'sku_unit_price' => $sku->unit_price,
                'quantity' => $skuData['quantity'],
            ]);
        }

        return response()->json(['message' => "Order created with success", "id" => $order->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $order;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->all());
        return response()->json(['message' => "Order modified with success", "id" => $order->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): void
    {
        $order->delete();
    }

    private function formatAddress(Address $address)
    {
        return sprintf(
            "%s %s %s %s %s %s",
            $address->firstname,
            $address->lastname,
            $address->first_address_line,
            $address->second_address_line ? $address->second_address_line . ', ' : '',
            $address->postal_code,
            $address->city
        );
    }
}
