<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service for building suggestion-related queries
 */
class SuggestionQueryService
{
    /**
     * Apply budget filters to a suggestions query
     *
     * @param mixed $query - Can be Builder or Relation
     * @param array $filters
     * @return mixed
     */
    public function applyBudgetFilters($query, array $filters)
    {
        if (!empty($filters['min_budget'])) {
            $query->where('min_budget', '>=', $filters['min_budget']);
        }
        
        if (!empty($filters['max_budget'])) {
            $query->where('max_budget', '<=', $filters['max_budget']);
        }

        return $query;
    }

    /**
     * Build a category query with suggestions and filters
     *
     * @param array $filters
     * @return Builder
     */
    public function buildCategoryQueryWithSuggestions(array $filters = []): Builder
    {
        $query = Category::with([
            'oneriler' => function ($query) use ($filters) {
                $this->applyBudgetFilters($query, $filters);
            },
            'oneriler.likes',
            'oneriler.createdBy'
        ])
        ->has('oneriler');

        // Apply category filter
        if (!empty($filters['category'])) {
            $query->where('id', $filters['category']);
        }

        // Apply location filters
        if (!empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }

        if (!empty($filters['neighborhood'])) {
            $query->where('neighborhood', $filters['neighborhood']);
        }

        // Apply status filter (voting status)
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->where('end_datetime', '>', now());
            } elseif ($filters['status'] === 'expired') {
                $query->where('end_datetime', '<=', now());
            }
        }

        // If budget filter exists, ensure categories have suggestions matching the budget
        if (!empty($filters['min_budget']) || !empty($filters['max_budget'])) {
            $query->whereHas('oneriler', function ($q) use ($filters) {
                $this->applyBudgetFilters($q, $filters);
            });
        }

        return $query;
    }

    /**
     * Get neighborhoods for a given district
     *
     * @param string|null $district
     * @return array
     */
    public function getNeighborhoodsForDistrict(?string $district): array
    {
        if (empty($district)) {
            return [];
        }

        return config('istanbul_neighborhoods.' . $district, []);
    }

    /**
     * Get all districts
     *
     * @return array
     */
    public function getAllDistricts(): array
    {
        return array_keys(config('istanbul_neighborhoods', []));
    }
}
