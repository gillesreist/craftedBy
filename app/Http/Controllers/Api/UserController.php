<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $user = User::create($request->all());
        return response()->json(['message' => "User created with success", "id" => $user->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user->isAdmin();
        return $user->load([
            'addresses', 'crafter', 'images', 'orders'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response()->json(['message' => "User updated with success", "id" => $user->id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
