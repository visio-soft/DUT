<?php

namespace App\View\Composers;

use App\Helpers\BackgroundImageHelper;
use Illuminate\View\View;

class UserBackgroundComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with([
            'hasBackgroundImages' => BackgroundImageHelper::hasBackgroundImages(),
            'randomBackgroundImage' => BackgroundImageHelper::getRandomSuggestionImage(),
            'backgroundImages' => BackgroundImageHelper::getRandomSuggestionImages(5),
            'carouselImageSets' => BackgroundImageHelper::getCarouselImages(3, 5),
        ]);
    }
}
