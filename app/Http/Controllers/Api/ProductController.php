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
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $products = Product::query()

        ->when($request->has('materials'), function ($query) use ($request) {
            $materials = explode(',', $request->query('materials', ''));
            $query->whereHas('materials', function ($query) use ($materials) {
                $query->whereIn('name', $materials);
            });
        })
        ->when($request->has('categories'), function ($query) use ($request) {
            $categories = explode(',', $request->query('categories', ''));
            $query->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('name', $categories);
            });
        })
        ->when($request->has('input'), function ($query) use ($request) {
            $input = $request->input('input');
            $keywords = explode(' ', $input);
            foreach ($keywords as $keyword) {
                $query->where('name', 'like', '%'.$keyword.'%');
            }
        })
        ->with([
            'categories','materials','skus.attributes' => function ($query) {
                $query->select('name', 'attribute_value');
            }
        ])
        // ->get()
        ->paginate(15)
        ;

        return $products;

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

        // Create and attach categories to product
        foreach ($request->categories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                $product->categories()->attach($category->id);
            }
        }

        // Create and attach materials to product
        foreach ($request->materials as $materialName) {
            $material = Material::where('name', $materialName)->first();

            if ($material) {
                $product->materials()->attach($material->id);
            }
        }

        // Create and associate customization to product
        if ($request->has('customization')) {
            $customization = Customization::where('name', $request->customization)->first();

            if ($customization) {
                $product->customization()->associate($customization->id);
            }
        }
        // Create and attach skus to product
        foreach ($request->skus as $skuData) {
            $skuData['product_id'] = $product->id;
            $sku = Sku::create([
                'product_id' => $skuData['product_id'],
                'name' => $skuData['name'],
                'unit_price' => $skuData['unit_price'],
                'status' => $skuData['status'],
                'is_active' => $skuData['is_active'],
                'stock' => $skuData['stock']
            ]);

            // Check for skus attributes and attach them
            if (isset($skuData['attributes']) && !empty($skuData['attributes'])) {
                foreach ($skuData['attributes'] as $attributeData) {

                    $attribute = Attribute::where('name', $attributeData['name'])->first();
                    $sku->attributes()->attach($attribute->id, ['attribute_value' => $attributeData['attribute_value']]);
                }
            }
        }

        return response()->json(['message' => "Product created with success", "id" => $product->id], 201);
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

        // Create and associate categories to product
        foreach ($request->categories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                $product->categories()->attach($category->id);
            }
        }

        // Create and associate materials to product
        foreach ($request->materials as $materialName) {
            $material = Material::where('name', $materialName)->first();

            if ($material) {
                $product->materials()->attach($material->id);
            }
        }

        // Create and associate customization to product
        if ($request->has('customization')) {
            $customization = Customization::where('name', $request->customization)->first();

            if ($customization) {
                $product->customization()->associate($customization->id);
            }
        }

        return response()->json(['message' => "Product modified with success", "id" => $product->id], 200);
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
