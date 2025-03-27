<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Exceptions\HelperNotSupported;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nette\Utils\Image;

class AssistanceRequestPhoto extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\AssistanceRequestPhoto>
     */
    public static $model = \App\Models\AssistanceRequestPhoto::class;

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
     * @throws HelperNotSupported
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Image Preview')
                ->onlyOnDetail()  // Only show it in the detail view
                ->asHtml()  // Tell Nova to render this as HTML
                ->displayUsing(function () {
                    // Log the base64 string to check if it is being fetched properly from the database
                    \Log::info('Base64 Image Data: ' . $this->image);  // Log the base64 string

                    if (empty($this->image)) {
                        \Log::warning('No base64 image data found.');
                        return 'No image available';
                    }

                    // Determine image type from the base64 string (you can use a better method depending on your needs)
                    $mimeType = 'image/png'; // Default to PNG, change to match your data

                    // Prepare the image tag with the base64 data
                    return "<img style='display:block; width:100px; height:100px;' src='data:{$mimeType};base64, {$this->image}' />";
                }),

            Date::make('Uploaded On', 'created_at')

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
