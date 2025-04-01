<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

class FetchLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch locations from an external API and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://www.fietsverhuurzeeland.nl/wp-json/wp/v2/locaties'; // Replace with actual API URL

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $locations = $response->json(); // Decode JSON response

                foreach ($locations as $item) {
                    $acf = $item['acf']; // Extract "acf" field

                    Location::updateOrCreate(
                        ['name' => $acf['naam_locatie']], // Prevent duplicate entries
                        [
                            'name' => $acf['naam_locatie'],
                            'street' => $acf['straat_locatie'],
                            'postal_code' => $acf['postcode_locatie'],
                            'city' => $acf['plaats_locatie'],
                            'phone' => $acf['phone_locatie'],
                            'email' => $acf['email_locatie'],
                            'address' => $acf['location_map']['address'] ?? null,
                            'latitude' => $acf['location_map']['lat'] ?? null,
                            'longitude' => $acf['location_map']['lng'] ?? null,
                        ]
                    );
                }

                $this->info('Locations successfully fetched and saved.');
                Log::info('Locations fetched successfully.');
            } else {
                $this->error('API request failed: ' . $response->status());
                Log::error('API request failed', ['status' => $response->status()]);
            }
        } catch (\Exception $e) {
            $this->error('Error fetching locations: ' . $e->getMessage());
            Log::error('Error fetching locations', ['error' => $e->getMessage()]);
        }
    }
}
