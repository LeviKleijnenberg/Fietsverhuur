<?php
namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class OpeningTime extends Resource
{
    public static $model = \App\Models\OpeningTime::class;

    public static $title = 'day';

    public static $search = ['id', 'day'];

    public static $displayInNavigation = true;


    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Location', 'location', Location::class)
                ->sortable(),

            Text::make('Day', 'day')->sortable(),

            Text::make('Start Time', 'start_time')->sortable(),
            Text::make('End Time', 'end_time')->sortable(),

            Boolean::make('Is Closed', 'is_closed')->sortable(),
        ];
    }
}
