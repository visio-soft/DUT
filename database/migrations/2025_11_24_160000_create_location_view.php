<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS location_view');
        DB::statement($this->viewSql());
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS location_view');
    }

    protected function viewSql(): string
    {
        return DB::getDriverName() === 'sqlite'
            ? $this->sqliteView()
            : $this->defaultView();
    }

    protected function sqliteView(): string
    {
        return <<<'SQL'
            CREATE VIEW location_view AS
            SELECT
                'country-' || c.id AS uid,
                c.id AS base_id,
                NULL AS parent_id,
                NULL AS parent_type,
                'country' AS type,
                c.name AS name,
                c.name AS country,
                NULL AS city,
                NULL AS district,
                NULL AS parent,
                c.name AS path,
                c.deleted_at
            FROM countries c
            UNION ALL
            SELECT
                'city-' || ci.id AS uid,
                ci.id AS base_id,
                ci.country_id AS parent_id,
                'country' AS parent_type,
                'city' AS type,
                ci.name AS name,
                co.name AS country,
                ci.name AS city,
                NULL AS district,
                co.name AS parent,
                co.name || ' / ' || ci.name AS path,
                ci.deleted_at
            FROM cities ci
            INNER JOIN countries co ON co.id = ci.country_id
            UNION ALL
            SELECT
                'district-' || d.id AS uid,
                d.id AS base_id,
                d.city_id AS parent_id,
                'city' AS parent_type,
                'district' AS type,
                d.name AS name,
                co.name AS country,
                ci.name AS city,
                d.name AS district,
                ci.name AS parent,
                co.name || ' / ' || ci.name || ' / ' || d.name AS path,
                d.deleted_at
            FROM districts d
            INNER JOIN cities ci ON ci.id = d.city_id
            INNER JOIN countries co ON co.id = ci.country_id
            UNION ALL
            SELECT
                'neighborhood-' || n.id AS uid,
                n.id AS base_id,
                n.district_id AS parent_id,
                'district' AS parent_type,
                'neighborhood' AS type,
                n.name AS name,
                co.name AS country,
                ci.name AS city,
                d.name AS district,
                d.name AS parent,
                co.name || ' / ' || ci.name || ' / ' || d.name || ' / ' || n.name AS path,
                n.deleted_at
            FROM neighborhoods n
            INNER JOIN districts d ON d.id = n.district_id
            INNER JOIN cities ci ON ci.id = d.city_id
            INNER JOIN countries co ON co.id = ci.country_id
        SQL;
    }

    protected function defaultView(): string
    {
        return <<<'SQL'
            CREATE VIEW location_view AS
            SELECT
                CONCAT('country-', c.id) AS uid,
                c.id AS base_id,
                NULL AS parent_id,
                NULL AS parent_type,
                'country' AS type,
                c.name AS name,
                c.name AS country,
                NULL AS city,
                NULL AS district,
                NULL AS parent,
                c.name AS path,
                c.deleted_at
            FROM countries c
            UNION ALL
            SELECT
                CONCAT('city-', ci.id) AS uid,
                ci.id AS base_id,
                ci.country_id AS parent_id,
                'country' AS parent_type,
                'city' AS type,
                ci.name AS name,
                co.name AS country,
                ci.name AS city,
                NULL AS district,
                co.name AS parent,
                CONCAT(co.name, ' / ', ci.name) AS path,
                ci.deleted_at
            FROM cities ci
            INNER JOIN countries co ON co.id = ci.country_id
            UNION ALL
            SELECT
                CONCAT('district-', d.id) AS uid,
                d.id AS base_id,
                d.city_id AS parent_id,
                'city' AS parent_type,
                'district' AS type,
                d.name AS name,
                co.name AS country,
                ci.name AS city,
                d.name AS district,
                ci.name AS parent,
                CONCAT(co.name, ' / ', ci.name, ' / ', d.name) AS path,
                d.deleted_at
            FROM districts d
            INNER JOIN cities ci ON ci.id = d.city_id
            INNER JOIN countries co ON co.id = ci.country_id
            UNION ALL
            SELECT
                CONCAT('neighborhood-', n.id) AS uid,
                n.id AS base_id,
                n.district_id AS parent_id,
                'district' AS parent_type,
                'neighborhood' AS type,
                n.name AS name,
                co.name AS country,
                ci.name AS city,
                d.name AS district,
                d.name AS parent,
                CONCAT(co.name, ' / ', ci.name, ' / ', d.name, ' / ', n.name) AS path,
                n.deleted_at
            FROM neighborhoods n
            INNER JOIN districts d ON d.id = n.district_id
            INNER JOIN cities ci ON ci.id = d.city_id
            INNER JOIN countries co ON co.id = ci.country_id
        SQL;
    }
};
