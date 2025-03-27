<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssistanceRequestPhoto;
use Illuminate\Http\Request;
use App\Models\AssistanceRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AssistanceRequestController extends Controller
{
    /**
     * Handle incoming API request to store an assistance request.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'problem'       => 'required|string',
            'bike_number'   => 'required|string',
            'location_id'   => 'required|integer|exists:locations,id',
            'image'         => 'nullable|string',
        ]);

        // Create map data
        $mapData = [
            'latlng' => [
                'lat' => $validated['latitude'],
                'lng' => $validated['longitude'],
            ]
        ];

        // Store the assistance request
        $assistanceRequest = AssistanceRequest::create([
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'problem'     => $validated['problem'],
            'bike_number' => $validated['bike_number'],
            'location_id' => $validated['location_id'],
            'map'         => json_encode($mapData),
        ]);

        Log::info('Assistance request created with ID: ' . $assistanceRequest->id);

        // Check if image is present
        if (!empty($validated['image'])) {
            try {
                Log::debug("Received base64 image data.");

                $imageData = $validated['image'];

                // If it has the data:image/... header
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                } else {
                    Log::debug("Base64 image has no header, assuming raw base64 string.");
                }

                // Decode the base64 string
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    Log::error("Base64 decode failed.");
                    throw new \Exception("Base64 decode failed.");
                }

                // Save base64 image string directly (as you requested)
                $photo = AssistanceRequestPhoto::create([
                    'assistance_request_id' => $assistanceRequest->id,
                    'image' => $validated['image']
                ]);

                $assistanceRequest->update([
                    'assistance_request_photos_id' => $photo->id
                ]);

                Log::debug("Base64 image saved to database with ID: " . $photo->id);

            } catch (\Exception $e) {
                Log::error("Error processing base64 image: " . $e->getMessage());
            }
        }

        Log::debug($request->all());

        return response()->json([
            'message' => 'Assistance request created successfully!',
            'data' => $assistanceRequest->load('images')
        ], 201);
    }
}
