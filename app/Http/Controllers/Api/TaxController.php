<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tax::class, 'tax');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Tax::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tax = Tax::create($request->all());
        return response()->json(['message' => "Tax created with success", "id" => $tax->id], 201);    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax)
    {
        return $tax;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tax $tax)
    {
        $tax->update($request->all());
        return response()->json(['message' => "Material modified with success", "id" => $tax->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax): void
    {
        $tax->delete();
    }
}
