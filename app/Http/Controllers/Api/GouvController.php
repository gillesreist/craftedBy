<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GouvController extends Controller
{
    public function index(Request $request)
    {
        $requestURL = 'https://api-adresse.data.gouv.fr/search/?q='.urlencode($request['query']);
        $response = Http::get($requestURL);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Erreur lors de la requête à l\'API'], $response->status());
        }
    }
}
