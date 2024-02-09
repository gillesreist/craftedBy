<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Image::class, 'image');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Image::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $image = Image::create($request->all());
        return response()->json(['message' => "Image created with success", "id" => $image->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        return $image;
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {        
        $image->update($request->all());
        return response()->json(['message' => "Image updated with success", "id" => $image->id], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $image->delete();
    }
}
