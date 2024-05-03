<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Sku;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Check user is authenticated
        $user = auth()->user();

        // Return empty data if he is not
        if (!$user) {
            return [];
        }

        return [
            'delivery_address' => [
                'required',
                Rule::exists('addresses', 'id')->where(static fn($query) => $query->where('user_id', auth()->id()))
            ],
            'facturation_address' => [
                Rule::exists('addresses', 'id')->where(static fn($query) => $query->where('user_id', auth()->id()))
            ],
            'skus' => 'required|array',
            'skus.*' => 'required',
            'skus.*.id' => 'required|exists:skus,id',
            'skus.*.quantity' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    // Get quantity
                    $quantity = $this->input($attribute);

                    // Check if there is at least a quantity of 1
                    if ($quantity <= 0) {
                        $fail('La quantité doit être supérieure à zéro.');
                        return;
                    }

                    // Get sku index in cart
                    $key = explode('.', $attribute)[1];

                    // Get sku id
                    $skuId = $this->input(sprintf('skus.%s.id', $key));

                    // Get sku
                    $sku = Sku::find($skuId);

                    // Check if quantity is not superior than sku's stock.
                    if ($quantity > $sku->stock) {
                        $fail('There is not enough in stock.');
                        return;
                    }
                },
            ],
        ];
    }
}
