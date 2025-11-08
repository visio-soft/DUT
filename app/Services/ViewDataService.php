<?php

namespace App\Services;

use App\Helpers\BackgroundImageHelper;

/**
 * Service to provide common view data across controllers
 */
class ViewDataService
{
    /**
     * Get background image data for views
     *
     * @return array
     */
    public function getBackgroundImageData(): array
    {
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return [
            'hasBackgroundImages' => $hasBackgroundImages,
            'randomBackgroundImage' => $randomBackgroundImage,
        ];
    }
}
