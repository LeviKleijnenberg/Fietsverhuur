<?php
namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Location extends Resource
{
    public static $model = \App\Models\Location::class;

    public static $title = 'location_name';

    public static $search = ['id', 'location_name'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Name', 'location_name')
                ->sortable()
                ->required(),

            Text::make('Address', 'location_address')->sortable(),
            Text::make('Phone', 'location_phone')->sortable(),
            Text::make('Email', 'location_email')->sortable(),
            Text::make('Full Address', 'address')->sortable(),
            Text::make('Location Code', 'location_code')->sortable()->onlyOnDetail(),
            Text::make('Street Number', 'street_number')->sortable()->onlyOnDetail(),
            Text::make('Street Name', 'street_name')->sortable()->onlyOnDetail(),
            Text::make('City', 'city')->sortable(),
            Text::make('State', 'state')->sortable(),
            Text::make('State Short', 'state_short')->sortable()->onlyOnDetail(),
            Text::make('Zip Code', 'post_code')->sortable()->onlyOnDetail(),
            Text::make('Country', 'country')->sortable(),
            Text::make('Country Short', 'country_short')->sortable()->onlyOnDetail(),
            Boolean::make('Visible', 'visible')->sortable()->onlyOnDetail(),


            // Relation: A location has many opening times
            HasMany::make('Opening Times', 'openingTimes', OpeningTime::class),

            Text::make('Location')
                ->onlyOnDetail()
                ->displayUsing(function () {
                    // Extensive logging
                    \Log::info('Maps Debugging', [
                        'raw_maps' => $this->map,
                        'maps_type' => gettype($this->map),
                    ]);

                    // Try multiple parsing approaches
                    try {
                        // If it's a string, try decoding
                        $mapsData = is_string($this->map)
                            ? json_decode($this->map, true)
                            : $this->map;

                        // Log parsed data
                        \Log::info('Parsed Maps Data', [
                            'parsed_data' => $mapsData,
                        ]);

                        // Extract coordinates with multiple fallback methods
                        $lat = $mapsData['latlng']['lat']
                            ?? $mapsData['lat']
                            ?? null;
                        $lng = $mapsData['latlng']['lng']
                            ?? $mapsData['lng']
                            ?? null;

                        // Log extracted coordinates
                        \Log::info('Extracted Coordinates', [
                            'latitude' => $lat,
                            'longitude' => $lng,
                        ]);

                        // Validate coordinates
                        if (is_numeric($lat) && is_numeric($lng)) {
                            return '<iframe
                            width="600"
                            height="450"
                            frameborder="0"
                            style="border:0;"
                            src="https://www.google.com/maps/embed/v1/place?key=' . env('GOOGLE_MAPS_API_KEY') .
                                '&q=' . trim($lat) . ',' . trim($lng) .
                                '&zoom=14"
                            allowfullscreen>
                        </iframe>';
                        }
                    } catch (\Exception $e) {
                        // Log any parsing errors
                        \Log::error('Maps Parsing Error', [
                            'error' => $e->getMessage(),
                            'maps_data' => $this->map,
                        ]);
                    }

                    // Fallback to default location
                    return '<iframe
                    width="600"
                    height="450"
                    frameborder="0"
                    style="border:0;"
                    src="https://www.google.com/maps/embed/v1/place?key=' . env('GOOGLE_MAPS_API_KEY') .
                        '&q=50.8466,4.3517&zoom=14"
                    allowfullscreen>
                </iframe>';
                })
                ->asHtml(),
        ];
    }
}
