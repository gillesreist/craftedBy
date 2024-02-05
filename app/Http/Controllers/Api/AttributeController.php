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
        return Attribute::with(['skus'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attribute = Attribute::create($request->all());
        return response()->json(['message' => "Attribut créé avec succès", "id" => $attribute->id], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        return $attribute->load([
            'skus.attributes' => function ($query) {
                $query->select('name', 'attribute_value');
            }
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $attribute->update($request->all());
        return response()->json(['message' => "Attribut modifié avec succès", "id" => $attribute->id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
    }
}
