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
        try {
            // Validate the request
            $validated = $request->validate([
                'latitude'      => 'required|numeric',
                'longitude'     => 'required|numeric',
                'problem'       => 'required|string',
                'bike_number'   => 'required|string',
                'location_id'   => 'required|integer|exists:locations,id',
                'images'        => 'nullable|array',
                'images.*.id'    => 'nullable|integer',
                'images.*.image' => 'nullable|string',
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

            // Handle and store images
            if (!empty($validated['images'])) {
                foreach ($validated['images'] as $imageData) {
                    try {
                        $image = $imageData['image'];


                        // Save the image as raw binary in the database
                        $photo = AssistanceRequestPhoto::create([
                            'assistance_request_id' => $assistanceRequest->id,
                            'image' => $image,  // Store as raw binary data
                        ]);

                        Log::info("Image saved to database with ID: " . $photo->id);

                    } catch (\Exception $e) {
                        Log::error("Error saving image: " . $e->getMessage());
                    }
                }
            }

            // Return success response with loaded images
            return response()->json([
                'message' => 'Assistance request created successfully!',
                'data' => $assistanceRequest->load('images')  // Load the images relationship
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        }
    }

}
