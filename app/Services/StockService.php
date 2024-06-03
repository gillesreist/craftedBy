<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Sku;
use Illuminate\Support\Facades\Log;


class StockService
{

    /**
     * Update the stock of a product.
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function updateStock(Order $order)
    {
        $skus = $order->skus;

        foreach ($skus as $sku) {
            $this->updateSkuStock($sku, $sku->pivot->quantity);
        }

        return response()->json(['message' => 'Stock updated successfully']);
    }

    private function updateSkuStock(Sku $sku, int $quantity)
    {
        $sku->stock -= $quantity;
        $sku->save();
    }
}
