<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Attribute::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attribute = Attribute::create($request->all());
        return response()->json(['message' => "Attribute created with success", "id" => $attribute->id], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        return $attribute->skus()->with(['attributes' => function ($query) {
            $query->select('attributes.*', 'attribute_sku.attribute_value');
        }])->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $attribute->update($request->all());
        return response()->json(['message' => "Attribute modified with success", "id" => $attribute->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
    }
}
