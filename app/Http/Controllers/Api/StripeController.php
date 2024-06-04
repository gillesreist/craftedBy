<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use App\Services\StockService;
use App\Services\InvoicesService;
use Illuminate\Support\Str;
use Stripe\Stripe as StripeGateway;

class StripeController extends Controller
{
    protected $stockService;
    protected $invoicesService;

    public function __construct(StockService $stockService,InvoicesService $invoicesService)
    {
        $this->stockService = $stockService;
        $this->invoicesService = $invoicesService;
    }


    public function initiatePayment(Request $request)
    {
        StripeGateway::setApiKey(env('STRIPE_SECRET'));

        $order = Order::find($request->order_id);


        try {
            $paymentIntent = PaymentIntent::create([
                

                'amount' => $order->price * 100, // Multiply as & when required
                'currency' => $request->currency,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $order->stripe_id = $paymentIntent->id;
            $order->save();

        } catch (Exception $e) {
            Log::error('Une erreur s\'est produite : ' . $e->getMessage());
        }

        return [
            'id' => $paymentIntent->id,
            'client_secret' => $paymentIntent->client_secret
        ];
    }


    public function completePayment(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        // Use the payment intent ID stored when initiating payment
        $paymentDetail = $stripe->paymentIntents->retrieve($request->stripe_id);

        if ($paymentDetail->status != 'succeeded') {
            return [
                'message' => (string) "erreur sur stripe"
            ];
        }

        // Complete the payment

        $order = Order::where('stripe_id', $request->stripe_id)->first();

        if ($order) {
            $order->status = OrderStatusEnum::PAYMENTVALIDATED->value;;

            $order->save();

            $this->stockService->updateStock($order);


            // return response()->json([
            //     'message' => 'Order status updated successfully',
            // ], 200);
            return $this->invoicesService->createInvoice($order);
        } else {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }
    }

    public function failPayment(Request $request)
    {
        // Log the failed payment if you wish
    }
}
