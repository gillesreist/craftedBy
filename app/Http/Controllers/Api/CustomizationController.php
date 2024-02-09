<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customization;
use Illuminate\Http\Request;

class CustomizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customization::class, 'customization');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Customization::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customization = Customization::create($request->all());
        return response()->json(['message' => "Customization created with success", "id" => $customization->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customization $customization)
    {
        return $customization;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customization $customization)
    {
        $customization->update($request->all());
        return response()->json(['message' => "Customization modified with success", "id" => $customization->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customization $customization)
    {
        $customization->delete();
    }
}
