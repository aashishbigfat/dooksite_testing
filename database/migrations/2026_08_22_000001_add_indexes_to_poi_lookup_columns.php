<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the indexes the POI lookups have always needed.
 *
 * departure_destination_point_of_interests only ever had PRIMARY(id) and an
 * index on poi_name, so every "WHERE reference_id = ?" and every join on
 * departure_id was a full table scan - which is what produced the ~7,800
 * rows-scanned-per-query figure in Cloud SQL Insights.
 *
 * Index-only and additive: no rows are touched and no query results change.
 *
 * NOTE: run this on its own, not via a bare `php artisan migrate`:
 *
 *     php artisan migrate --path=database/migrations/2026_08_22_000001_add_indexes_to_poi_lookup_columns.php
 *
 * The migrations table in this database records the pre-Laravel-11 filenames
 * (2014_10_12_000000_create_users_table, ...) while the files on disk use the
 * new 0001_01_01_* names, so a bare `migrate` would consider the default
 * migrations pending and fail trying to re-create existing tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departure_destination_point_of_interests', function (Blueprint $table) {
            if (! Schema::hasIndex('departure_destination_point_of_interests', 'ddpoi_reference_id_index')) {
                $table->index('reference_id', 'ddpoi_reference_id_index');
            }

            if (! Schema::hasIndex('departure_destination_point_of_interests', 'ddpoi_departure_id_index')) {
                $table->index('departure_id', 'ddpoi_departure_id_index');
            }

            if (! Schema::hasIndex('departure_destination_point_of_interests', 'ddpoi_destination_id_index')) {
                $table->index('destination_id', 'ddpoi_destination_id_index');
            }
        });

        // The counting queries filter departures on these three together.
        Schema::table('departures', function (Blueprint $table) {
            if (! Schema::hasIndex('departures', 'departures_status_featured_dep_type_index')) {
                $table->index(['status', 'featured', 'dep_type'], 'departures_status_featured_dep_type_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('departure_destination_point_of_interests', function (Blueprint $table) {
            $table->dropIndex('ddpoi_reference_id_index');
            $table->dropIndex('ddpoi_departure_id_index');
            $table->dropIndex('ddpoi_destination_id_index');
        });

        Schema::table('departures', function (Blueprint $table) {
            $table->dropIndex('departures_status_featured_dep_type_index');
        });
    }
};
