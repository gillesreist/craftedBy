<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status'),
            'price' => $request->input('price'),
            'date' => $request->input('date'),
            'delivery_address' => $request->input('delivery_address'),
            'facturation_address' => $request->input('facturation_address')
        ]);


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
    public function destroy(Order $order)
    {
        $order->delete();
    }
}
