<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Customization;
use App\Models\Material;
use App\Models\Product;
use App\Models\Sku;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with([
            'categories', 'materials', 'customization',
            'skus.attributes' => function ($query) {
                $query->select('name', 'attribute_value');
            }
        ])
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Valider le nouveau produit

        //product has categories
        if (!$request->has('categories') || !is_array($request->categories) || empty($request->categories)) {
            return response()->json(['message' => "No category associated"], 400);
        }

        //categories exist
        foreach ($request->categories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();
            if (!$category) {
                return response()->json(['message' => "Associated category does not exist"], 400);
            }
        }

        //product has materials
        if (!$request->has('materials') || !is_array($request->materials) || empty($request->materials)) {
            return response()->json(['message' => "No material associated"], 400);
        }

        //materials exist
        foreach ($request->materials as $materialName) {
            $material = Material::where('name', $materialName)->first();
            if (!$material) {
                return response()->json(['message' => "Associated material does not exist"], 400);
            }
        }

        //customization exists if product customizable
        if ($request->has('customization')) {
            $customization = Customization::where('name', $request->customization)->first();

            if (!$customization) {
                return response()->json(['message' => "Associated customization does not exist"], 400);
            }
        }

        //product has skus
        if (!$request->has('skus') || !is_array($request->skus) || empty($request->skus)) {
            return response()->json(['message' => "No sku associated"], 400);
        }

        //skus validation
        foreach ($request->skus as $skuData) {
            if (
                !isset($skuData['name'], $skuData['unit_price'], $skuData['status'], $skuData['is_active']) ||
                !is_string($skuData['name']) ||
                !is_float($skuData['unit_price']) ||
                !is_int($skuData['is_active']) ||
                !is_int($skuData['status'])
            ) {
                return response()->json(['message' => "Sku isn't complete"], 400);
            }

            if (count($request->skus) > 1 && (!isset($skuData['attributes']) || empty($skuData['attributes']))) {
                return response()->json(['message' => "Skus must have attributes if more than one"], 400);
            }

            if (isset($skuData['attributes']) && !empty($skuData['attributes'])) {
                foreach ($skuData['attributes'] as $attributeData) {

                    $attribute = Attribute::where('name', $attributeData['name'])->first();
                    if (!$attribute) {
                        return response()->json(['message' => "This attribute doesn't exist"], 400);
                    }

                    if (!isset($attributeData["attribute_value"]) || !is_string($attributeData["attribute_value"])) {
                        return response()->json(['message' => "Attribute has no value"], 400);
                    }
                }
            }

            //sku doesn't have same attribute several times
            $attributeNames = array_column($skuData['attributes'], 'name');

            if (!(count($attributeNames) === count(array_unique($attributeNames)))) {
                return response()->json(['message' => "Sku have several times the same attribute"], 400);
            }
        }

        // Créer le produit
        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'crafter_id' => $request->input('crafter_id')
        ]);

        // Créer et attacher chaque catégorie au produit
        foreach ($request->categories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                // Attachez la catégorie au produit
                $product->categories()->attach($category->id);
            }
        }

        // Créer et attacher chaque material au produit
        foreach ($request->materials as $materialName) {
            $material = Material::where('name', $materialName)->first();

            if ($material) {
                // Attachez le material au produit
                $product->materials()->attach($material->id);
            }
        }

        // Vérifier la customization
        if ($request->has('customization')) {
            // Créer et attacher la customization au produit
            $customization = Customization::where('name', $request->customization)->first();

            if ($customization) {
                $product->customization()->associate($customization->id);
            }
        }
        // Créer et attacher chaque Sku au produit
        foreach ($request->skus as $skuData) {
            $skuData['product_id'] = $product->id;
            $sku = Sku::create([
                'product_id' => $skuData['product_id'],
                'name' => $skuData['name'],
                'unit_price' => $skuData['unit_price'],
                'status' => $skuData['status'],
                'is_active' => $skuData['is_active'],
            ]);

            // Vérifier si les Skus ont des attributes
            if (isset($skuData['attributes']) && !empty($skuData['attributes'])) {
                foreach ($skuData['attributes'] as $attributeData) {

                    $attribute = Attribute::where('name', $attributeData['name'])->first();
                    $sku->attributes()->attach($attribute->id, ['attribute_value' => $attributeData['attribute_value']]);
                }
            }
        }





        return response()->json(['message' => "Produit créé avec succès", "id" => $product->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product->load([
            'categories', 'materials', 'customization',
            'skus.attributes' => function ($query) {
                $query->select('name', 'attribute_value');
            }
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $skus = $product->skus;
        foreach ($skus as $sku) {
            $sku->attributes()->detach();
            $sku->delete();
        }

        $product->categories()->detach();
        $product->materials()->detach();
        $product->customization()->dissociate();

        $product->delete();
    }
}
