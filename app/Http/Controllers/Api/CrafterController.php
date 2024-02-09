<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crafter;
use Illuminate\Http\Request;

class CrafterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Crafter::class, 'crafter');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Crafter::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $crafter = Crafter::create($request->all());
        return response()->json(['message' => "Crafter created with success", "id" => $crafter->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Crafter $crafter)
    {
        return $crafter->load(['images','user','products']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Crafter $crafter)
    {        
        $crafter->update($request->all());
        return response()->json(['message' => "Crafter updated with success", "id" => $crafter->id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crafter $crafter)
    {
        $crafter->delete();
    }
}
