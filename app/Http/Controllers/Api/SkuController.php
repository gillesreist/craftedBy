<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Sku;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Sku::class, 'sku');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Sku::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sku = Sku::create([
            'product_id' => $request->input('product_id'),
            'name' => $request->input('name'),
            'unit_price' => $request->input('unit_price'),
            'status' => $request->input('status'),
            'stock' => $request->input('stock'),
            'is_active' => $request->input('is_active'),
        ]);

        $attributes = $request->input('attributes');

        // Check for skus attributes and attach them
        if (isset($attributes) && !empty($attributes)) {
            foreach ($attributes as $attributeData) {

                $attribute = Attribute::where('name', $attributeData['name'])->first();
                $sku->attributes()->attach($attribute->id, ['attribute_value' => $attributeData['attribute_value']]);
            }
        }

        return response()->json(['message' => "Sku created with success", "id" => $sku->id], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Sku $sku)
    {
        return $sku->load(['attributes' => function ($query) {
            $query->select('name', 'attribute_value');
        }]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sku $sku)
    {
        $sku->update([
            'name' => $request->input('name'),
            'unit_price' => $request->input('unit_price'),
            'status' => $request->input('status'),
            'is_active' => $request->input('is_active'),
        ]);

        $sku->attributes()->detach();

        $attributes = $request->input('attributes');

        // Check for skus attributes and attach them
        if (isset($attributes) && !empty($attributes)) {
            foreach ($attributes as $attributeData) {

                $attribute = Attribute::where('name', $attributeData['name'])->first();
                $sku->attributes()->attach($attribute->id, ['attribute_value' => $attributeData['attribute_value']]);
            }
        }

        return response()->json(['message' => "Sku modified with success", "id" => $sku->id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sku $sku)
    {
        $sku->attributes()->detach();
        $sku->delete();
    }
}
