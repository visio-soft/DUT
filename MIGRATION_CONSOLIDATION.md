# Migration Consolidation Documentation

## Overview
The database migrations have been consolidated from 24 separate files to 5 focused migrations to improve maintainability and clarity.

## Consolidated Migration Structure

### Core Laravel Migrations (Unchanged)
1. `0001_01_01_000000_create_users_table.php` - Laravel default user system
2. `0001_01_01_000001_create_cache_table.php` - Laravel cache system
3. `0001_01_01_000002_create_jobs_table.php` - Laravel queue system

### New Consolidated Migrations
4. `2025_01_01_000000_create_initial_database_schema.php` - Complete database schema setup
5. `2025_01_01_000001_add_soft_deletes_to_users.php` - User soft deletion support

## Tables Created by Consolidated Migration

### Authentication & Authorization (Spatie Laravel-Permission)
- `permissions` - System permissions
- `roles` - User roles
- `model_has_permissions` - Permission assignments to models
- `model_has_roles` - Role assignments to models  
- `role_has_permissions` - Permissions assigned to roles

### Core Application Tables
- `categories` - Project categories with location and timing information
- `oneriler` - Main proposals/projects table with comprehensive fields
- `media` - File attachments (Spatie Media Library)

### Social Features
- `oneri_likes` - User likes on proposals
- `oneri_comments` - Comments on proposals with approval system

## Key Features of the Consolidated Schema

### Categories Table
- Simplified structure (no hierarchy)
- Location fields (district, neighborhood, detailed_address, country, province)
- Timing fields (start_datetime, end_datetime)
- Soft deletes enabled

### Oneriler (Proposals) Table
- Complete project information (title, description, budget)
- Timing with estimated_duration and optional start/end dates
- Location data (coordinates and address details)
- User relationships (created_by, updated_by)
- Cascade deletion on category removal
- Soft deletes enabled

### Social Features
- Like system with unique constraints
- Comment system with approval workflow
- Soft deletes on comments

## Original Migrations Consolidated

The following 21 migration files were consolidated:

**Initial Setup:**
- `2025_08_31_000000_rename_projects_table_to_oneriler.php`
- `2025_08_31_194706_create_database_tables.php`
- `2025_08_31_200231_create_media_table.php`

**Oneriler Table Evolution:**
- `2025_09_01_131946_add_updated_by_id_to_projects_table.php`
- `2025_09_01_141500_add_design_landscape_to_projects_table.php`
- `2025_09_03_083637_replace_start_date_with_estimated_duration_in_oneriler_table.php`
- `2025_09_08_212543_make_oneri_fields_optional.php`
- `2025_09_19_194517_add_project_datetime_fields_to_oneriler_table.php`
- `2025_09_19_200153_make_category_id_required_again_in_oneriler_table.php`
- `2025_09_20_200000_update_category_foreign_key_cascade.php`

**Categories Table Evolution:**
- `2025_09_02_000001_remove_category_hierarchy.php`
- `2025_09_02_181403_add_time_fields_to_categories_table.php`
- `2025_09_19_192923_add_description_to_categories_table.php`
- `2025_09_20_175517_add_location_fields_to_categories_table.php`

**Design System Removal:**
- `2025_09_18_193914_add_soft_deletes_to_project_designs_table.php`
- `2025_09_19_181600_remove_design_functionality.php`

**User System Updates:**
- `2025_09_18_193933_add_soft_deletes_to_users_table.php`

**New Features:**
- `2025_09_19_184215_create_oneri_likes_table.php`
- `2025_09_19_191104_drop_objeler_table.php`
- `2025_09_19_192345_create_oneri_comments_table.php`

## Benefits of Consolidation

1. **Reduced Complexity**: From 24 files to 5 files
2. **Better Organization**: Related functionality grouped together
3. **Improved Maintainability**: Single source of truth for schema
4. **Cleaner Git History**: Fewer migration files to track
5. **Faster Setup**: New environments can be set up with fewer migration steps
6. **Schema Clarity**: Final database structure is immediately apparent

## Backup
All original migration files have been backed up to `database/migrations_backup/` for reference.