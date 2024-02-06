<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
            'crafter_id' => 'required|exists:crafters,id',
            'categories' => 'required|array',
            'categories.*' => 'required|exists:categories,name|distinct',
            'materials' => 'required|array',
            'materials.*' => 'required|exists:materials,name|distinct',
            'customization' => 'nullable|string|exists:customizations,name',
            'skus' => 'required|array',
            'skus.*' => 'required',
            'skus.*.name' => 'required|string|max:255',
            'skus.*.unit_price' => 'required|numeric',
            'skus.*.status' => 'required|integer',
            'skus.*.is_active' => 'required|integer',

            //sku must have attributes if more than one sku
            'skus.*.attributes' => [
                Rule::requiredIf(function () {
                    return count(request('skus')) > 1;
                }),
                'array'
            ],

            //each sku doesn't have same attribute several times
            'skus.*.attributes' => [
                (function ($attribute, $value, $fail) {
                    $names = array_column($value, 'name');
                    if (count($names) !== count(array_unique($names))) {
                        $fail('The attribute names must be unique among all skus.');
                    }

                    return true;
                }),
            ],

            //each sku must not have the same attributes combination
            'skus' => [
                function ($attribute, $value, $fail) {
                    // For stocking each sku attributes combination
                    $attributeValues = [];

                    foreach ($value as $skuData) {
                        // Check if there is attributes
                        if (isset($skuData['attributes']) && is_array($skuData['attributes'])) {
                            $attributes = $skuData['attributes'];

                            // Sort attributes by names
                            usort($attributes, function ($a, $b) {
                                return $a['name'] <=> $b['name'];
                            });

                            // Generate key for this set of attributes
                            $key = json_encode($attributes);

                            // Check if this key already exists
                            if (isset($allAttributeCombinations[$key])) {
                                $fail("Attribute combinations must be different for each sku.");
                            }

                            // Stock this key
                            $allAttributeCombinations[$key] = true;
                        }
                    }
                },
            ],

        ];
    }
}
