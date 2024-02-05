<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
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
    public function store(StoreProductRequest $request)
    {
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

            // Vérifier si les Skus ont des attributes et les attacher
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
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        $product->categories()->detach();
        $product->materials()->detach();
        $product->customization()->dissociate();

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

        return response()->json(['message' => "Produit modifié avec succès", "id" => $product->id], 201);
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
