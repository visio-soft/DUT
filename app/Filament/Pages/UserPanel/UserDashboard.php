<?php

namespace App\Filament\Pages\UserPanel;

use App\Helpers\BackgroundImageHelper;
use App\Models\Project;

class UserDashboard
{
    /**
     * Handle the user dashboard index page
     */
    public function index()
    {
        $randomProjects = Project::query()
            ->with(['suggestions' => function ($query) {
                $query->with('likes')->latest()->limit(3);
            }])
            ->withCount('suggestions')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $backgroundData = $this->getBackgroundImageData();

        return view('filament.pages.user-panel.user-dashboard', array_merge(compact('randomProjects'), $backgroundData));
    }

    /**
     * Get background image data for views
     */
    private function getBackgroundImageData(): array
    {
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return compact('hasBackgroundImages', 'randomBackgroundImage');
    }
}
