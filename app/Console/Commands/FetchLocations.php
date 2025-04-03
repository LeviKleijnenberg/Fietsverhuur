<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\OpeningTime;
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

                    Company::updateOrCreate(
                        ['id' => $item['id']],  // Use API response ID
                        ['name' => $acf['naam_locatie']]
                    );


                    // Fetch the company to get the correct ID
                    $company = Company::where('id', $item['id'])->first();

                    // Ensure company_id is correctly assigned before inserting the location
                    if (empty($company->id)) {
                        Log::error("Company ID is missing for location: " . $acf['naam_locatie']);
                        continue; // Skip this iteration if no valid company_id
                    }

                    // Now, insert the location with the correct company_id
                   $location = Location::updateOrCreate(
                        ['location_code' => $acf['location_map']['place_id']], // Prevent duplicate entries
                        [

                            'company_id' => $company->id, // ✅ Now it's correctly set
                            'location_name' => $acf['naam_locatie'],
                            'location_address' => $acf['location_map']['address'],
                            'postal_code' => $acf['postcode_locatie'],
                            'location_phone' => $acf['phone_locatie'],
                            'location_email' => $acf['email_locatie'],
                            'address' => $acf['location_map']['address'] ?? null,
                            'zoom' => $acf['location_map']['zoom'] ?? null,
                            'street_name' => $acf['location_map']['street_name'],
                            'street_number' => $acf['location_map']['street_number'],
                            'city' => $acf['location_map']['city'],
                            'state' => $acf['location_map']['state'],
                            'state_short' => $acf['location_map']['state_short'],
                            'post_code' => $acf['location_map']['post_code'],
                            'country' => $acf['location_map']['country'],
                            'country_short' => $acf['location_map']['country_short'],
                            'visible' => ($acf['hide_on_overview'] ?? '') === 'hide' ? 0 : 1, // ✅ Convert "hide" to 0 and "show" to 1
                            'reservation_url' => $acf['reserveer_url'] ?? null,
                            'map' => json_encode([
                                'latlng' => [
                                    'lat' => (string) ($acf['location_map']['lat'] ?? ''),
                                    'lng' => (string) ($acf['location_map']['lng'] ?? '')
                                ]
                            ]),
                        ]
                    );

                    // Now that the location is created, update the company with the location_id
                    $company->location_id = $location->id; // Add the location ID to the company
                    $company->save(); // Save the company

                    if (!empty($acf['openingstijden'])) {
                        foreach ($acf['openingstijden'] as $openingTime) {
                            Log::info('Opening Time Data:', [
                                'day' => $openingTime['dag'],
                                'start_time' => $openingTime['starttijd'] ?? 'MISSING',
                                'end_time' => $openingTime['eindtijd'] ?? 'MISSING',
                                'is_closed' => $openingTime['gesloten'] ?? 'MISSING',
                            ]);

                            OpeningTime::updateOrCreate(
                                [
                                    'location_id' => $location->id, // ✅ Associate with the correct location
                                    'day' => $openingTime['dag'],
                                ],
                                [
                                    'start_time' => !empty($openingTime['starttijd']) ? date("H:i:s", strtotime($openingTime['starttijd'])) : null,
                                    'end_time' => !empty($openingTime['eindtijd']) ? date("H:i:s", strtotime($openingTime['eindtijd'])) : null,

                                    'is_closed' => isset($openingTime['gesloten']) && $openingTime['gesloten'] ? 1 : 0,
                                ]
                            );
                        }
                    }
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
