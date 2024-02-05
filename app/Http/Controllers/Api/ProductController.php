<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        return Product::with(['categories', 'materials', 'customization', 
        'skus.attributes' => function ($query) {
            $query->select('name', 'attribute_value');
        }])
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Créer le produit
        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'crafter_id' => $request->input('crafter_id')
        ]);

        // Vérifier les catégories
        if ($request->has('categories') && is_array($request->categories)) {
            // Créer et attacher chaque catégorie au produit
            foreach ($request->categories as $categoryName) {
                $category = Category::where('name', $categoryName)->first();

                if ($category) {
                    // Attachez la catégorie au produit
                    $product->categories()->attach($category->id);
                }
            }
        }

        // Vérifier si des Skus sont fournis dans la requête
        if ($request->has('skus') && is_array($request->skus)) {
            // Créer et attacher chaque Sku au produit
            foreach ($request->skus as $skuData) {
                $skuData['product_id'] = $product->id;
                $sku = Sku::create($skuData);
            }
        }

        return response()->json(['message' => "Produit créé avec succès", "id" => $product->id], 201);    
}

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product->load(['categories', 'materials', 'customization', 
        'skus.attributes' => function ($query) {
            $query->select('name', 'attribute_value');
        }]);
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
            $sku->delete();
        }

        $product->categories()->detach();
        
        $product->delete();
    }
}
