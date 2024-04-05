<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Address::class, 'address');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $addresses = Address::query()
        ->when($request->has('username'), function ($query) use ($request) {
            $userId = User::where('email', $request->input('username'))->value('id');
            $query->where('user_id', $userId);
        })
        ->get();
        return $addresses;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $address = Address::create($request->all());
        return response()->json(['message' => "Address created with success", "id" => $address->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return $address->load(['user']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {        
        $address->update($request->all());
        return response()->json(['message' => "Address updated with success", "id" => $address->id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete();
    }
}
