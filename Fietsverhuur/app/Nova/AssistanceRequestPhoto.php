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
                ->asHtml()
                ->displayUsing(function () {
                    if (empty($this->image)) {
                        return 'No image available';
                    }

                    // Ensure base64 is clean
                    $cleanBase64 = trim($this->image);

                    // Manually set MIME type (You can improve this by detecting dynamically)
                    $mimeType = 'image/jpeg'; // Change based on your data

                    // Log the full base64 string for debugging
                    \Log::info('Final Base64 Image:', [$cleanBase64]);

                    // Return the image HTML
                    return "<img src='data:{$mimeType};base64,{$cleanBase64}' style='display:block; width:100px; height:100px; object-fit:cover;' />";
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
