<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Material::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $material = Material::create($request->all());
        return response()->json(['message' => "Material created with success", "id" => $material->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return $material->products()->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $material->update($request->all());
        return response()->json(['message' => "Material modified with success", "id" => $material->id], 200);    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();
    }
}
