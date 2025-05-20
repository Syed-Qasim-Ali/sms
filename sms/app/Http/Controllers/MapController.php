<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Store API key in .env
        $origin = $request->input('origin', 'New York, NY');
        $destination = $request->input('destination', 'Los Angeles, CA');

        $response = Http::get("https://maps.googleapis.com/maps/api/directions/json", [
            'origin' => $origin,
            'destination' => $destination,
            'key' => $apiKey
        ]);

        return response()->json($response->json());
    }
}
