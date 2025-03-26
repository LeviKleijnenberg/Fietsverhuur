<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaGoogleMaps\GoogleMaps;

class AssistanceRequests extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\AssistanceRequest>
     */
    public static $model = \App\Models\AssistanceRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('description')
            ->sortable(),

            BelongsTo::make('Bike number', 'bike', Bikes::class)
                ->sortable()
                ->displayUsing(function ($bike) {
                    return $bike ? $bike->bike_number : '';  // Assuming 'model' is the field in the Bike model you want to display
                }),

            BelongsTo::make('Company Address', 'location', Locations::class)
                ->sortable()
                ->displayUsing(function ($location) {
                    return $location ? $location->location_address : 'No Address Available';
                }),

            HasMany::make('Photo ID', 'photos', AssistanceRequestPhoto::class)
                ->sortable()
                ->displayUsing(function ($photo) {
                    return $photo ? $photo->id : 'No Photo';
                }),

            Text::make('Current Location')
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

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
