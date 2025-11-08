<?php

namespace App\Helpers;

use App\Models\Suggestion;

class BackgroundImageHelper
{
    /**
     * Get a random suggestion image URL for background use
     * Returns null if no images are available
     */
    public static function getRandomSuggestionImage(): ?string
    {
        $suggestion = Suggestion::whereHas('media', function ($query) {
            $query->where('collection_name', 'images');
        })->inRandomOrder()->first();

        if ($suggestion && $suggestion->hasMedia('images')) {
            return $suggestion->getFirstMediaUrl('images');
        }

        return null;
    }

    /**
     * Get multiple random suggestion images for variety
     */
    public static function getRandomSuggestionImages(int $count = 5): array
    {
        $suggestions = Suggestion::whereHas('media', function ($query) {
            $query->where('collection_name', 'images');
        })->inRandomOrder()->limit($count * 2)->get(); // Get more to ensure variety

        $images = [];
        foreach ($suggestions as $suggestion) {
            if ($suggestion->hasMedia('images') && count($images) < $count) {
                $images[] = [
                    'url' => $suggestion->getFirstMediaUrl('images'),
                    'title' => $suggestion->title,
                    'id' => $suggestion->id
                ];
            }
        }

        return $images;
    }

    /**
     * Get carousel set of images (more images for rotation)
     */
    public static function getCarouselImages(int $setCount = 3, int $imagesPerSet = 5): array
    {
        $totalNeeded = $setCount * $imagesPerSet;
        $suggestions = Suggestion::whereHas('media', function ($query) {
            $query->where('collection_name', 'images');
        })->inRandomOrder()->limit($totalNeeded * 2)->get();

        $images = [];
        foreach ($suggestions as $suggestion) {
            if ($suggestion->hasMedia('images') && count($images) < $totalNeeded) {
                $images[] = [
                    'url' => $suggestion->getFirstMediaUrl('images'),
                    'title' => $suggestion->title,
                    'id' => $suggestion->id
                ];
            }
        }

        // Group images into sets
        $sets = [];
        for ($i = 0; $i < $setCount; $i++) {
            $sets[] = array_slice($images, $i * $imagesPerSet, $imagesPerSet);
        }

        return $sets;
    }

    /**
     * Get a single random suggestion image that changes on each request
     * Perfect for user panel pages where we want one random image per page refresh
     */
    public static function getRandomBackgroundImage(): ?array
    {
        $suggestion = Suggestion::whereHas('media', function ($query) {
            $query->where('collection_name', 'images');
        })->inRandomOrder()->first();

        if ($suggestion && $suggestion->hasMedia('images')) {
            return [
                'url' => $suggestion->getFirstMediaUrl('images'),
                'title' => $suggestion->title,
                'id' => $suggestion->id
            ];
        }

        return null;
    }

    /**
     * Check if background images are available
     */
    public static function hasBackgroundImages(): bool
    {
        return Suggestion::whereHas('media', function ($query) {
            $query->where('collection_name', 'images');
        })->exists();
    }
}
