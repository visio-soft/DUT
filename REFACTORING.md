# Code Naming and Structure Documentation

## Overview
This document describes the naming conventions used in the DUT (Democratic Urban Transformation) project and provides guidance for future refactoring.

## Current Model Naming

### Turkish Names (Current Implementation)
The following models use Turkish naming:

| Turkish Name | English Translation | Database Table | Purpose |
|--------------|-------------------|----------------|---------|
| `Oneri` | Suggestion | `oneriler` | Citizen suggestions for urban projects |
| `OneriComment` | SuggestionComment | `oneri_comments` | Comments on suggestions |
| `OneriLike` | SuggestionLike | `oneri_likes` | Likes/votes for suggestions |
| `OneriCommentLike` | SuggestionCommentLike | `oneri_comment_likes` | Likes for comments |

### Why Turkish Names Exist
1. **Legacy Code**: The project was initially developed with Turkish naming conventions
2. **User Base**: Primary users are Turkish government employees and citizens
3. **Database Constraints**: Existing production data uses these table names

## Services (Newly Created)

The following services have been created to consolidate repeated code:

### ViewDataService
- **Purpose**: Provides common view data across controllers
- **Location**: `app/Services/ViewDataService.php`
- **Methods**:
  - `getBackgroundImageData()`: Returns background image configuration

### SuggestionQueryService  
- **Purpose**: Builds queries for suggestions with filters
- **Location**: `app/Services/SuggestionQueryService.php`
- **Methods**:
  - `applyBudgetFilters($query, $filters)`: Applies budget filters to queries
  - `buildCategoryQueryWithSuggestions($filters)`: Builds category queries with suggestions
  - `getNeighborhoodsForDistrict($district)`: Gets neighborhoods for a district
  - `getAllDistricts()`: Gets all Istanbul districts

## Refactoring Completed

### Code Consolidation
✅ **Completed**: Extracted repeated code patterns into services
- Removed ~60 lines of duplicated background image logic
- Centralized query building logic
- Created reusable filter application methods

## Future Refactoring Recommendations

### Phase 1: Preparation (Low Risk)
1. Add English method aliases to existing models
2. Update inline documentation to English
3. Create comprehensive test coverage for all features

### Phase 2: Model Renaming (Medium Risk)  
1. Create new model classes with English names that extend the Turkish ones
2. Gradually migrate code to use new English names
3. Keep Turkish names as deprecated aliases

### Phase 3: Database Migration (High Risk)
⚠️ **Warning**: Only proceed after thorough testing and data backup

1. Create migrations to rename tables:
   - `oneriler` → `suggestions`
   - `oneri_comments` → `suggestion_comments`  
   - `oneri_likes` → `suggestion_likes`
   - `oneri_comment_likes` → `suggestion_comment_likes`

2. Update all foreign key constraints
3. Update all indexes
4. Run extensive tests

### Phase 4: Cleanup (Low Risk)
1. Remove deprecated Turkish model names
2. Update all documentation
3. Remove migration files for old table names

## Guidelines for New Code

When adding new features:

1. **Use English naming** for new models, methods, and variables
2. **Keep Turkish labels** for user-facing text (admin panel, forms)
3. **Use services** instead of duplicating code
4. **Follow Laravel conventions** for naming (PascalCase for classes, camelCase for methods)

## Example Naming Conventions

### Good Examples
```php
// Service usage
$backgroundData = $this->viewDataService->getBackgroundImageData();

// Query building
$projects = $this->suggestionQueryService->buildCategoryQueryWithSuggestions($filters);

// Variable naming
$suggestionId = $request->input('id');
$hasBackgroundImages = $this->checkBackgroundImages();
```

### Avoid
```php
// Duplicating background image logic inline
$hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
$randomBackgroundImage = null;
if ($hasBackgroundImages) {
    $imageData = BackgroundImageHelper::getRandomBackgroundImage();
    $randomBackgroundImage = $imageData ? $imageData['url'] : null;
}

// Inline query building
$query = Category::with(['oneriler' => function($q) use ($filters) {
    if (!empty($filters['min_budget'])) {
        $q->where('min_budget', '>=', $filters['min_budget']);
    }
    // ... more duplicated logic
}]);
```

## Testing

All refactoring changes must maintain existing test coverage:
- Current test status: 10 passing, 2 pre-existing failures (unrelated to refactoring)
- Tests must pass before and after refactoring
- New services should have their own unit tests

## References

- Laravel Naming Conventions: https://laravel.com/docs/naming-conventions
- PSR-4 Autoloading: https://www.php-fig.org/psr/psr-4/
- Service Pattern in Laravel: https://laravel.com/docs/providers
