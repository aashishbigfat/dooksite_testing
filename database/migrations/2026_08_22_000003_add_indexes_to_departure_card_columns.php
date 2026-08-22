<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the indexes the tour-package card lookups have always needed.
 *
 * All three tables only had PRIMARY(id), so every "WHERE departure_id = ?"
 * behind a card was a full table scan. inclusions is the expensive one at
 * ~30,000 rows: EXPLAIN reported type=ALL, key=NULL, rows=30041 for a single
 * card, and a page renders six of them.
 *
 * hotel_categories gets a composite so the "cheapest category" lookup can read
 * the price straight from the index instead of sorting (it reported
 * "Using filesort").
 *
 * Index-only and additive: no rows are touched and no query results change.
 *
 * NOTE: run this on its own, not via a bare `php artisan migrate` - see the
 * note in 2026_08_22_000001_add_indexes_to_poi_lookup_columns.php.
 *
 *     php artisan migrate --path=database/migrations/2026_08_22_000003_add_indexes_to_departure_card_columns.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inclusions', function (Blueprint $table) {
            if (! Schema::hasIndex('inclusions', 'inclusions_departure_id_index')) {
                // icon_inclusion_id rides along so the join to icon_inclusions
                // can be resolved from the index alone.
                $table->index(['departure_id', 'icon_inclusion_id'], 'inclusions_departure_id_index');
            }
        });

        Schema::table('departure_destinations', function (Blueprint $table) {
            if (! Schema::hasIndex('departure_destinations', 'departure_destinations_departure_id_index')) {
                $table->index('departure_id', 'departure_destinations_departure_id_index');
            }
        });

        Schema::table('hotel_categories', function (Blueprint $table) {
            if (! Schema::hasIndex('hotel_categories', 'hotel_categories_departure_id_price_index')) {
                $table->index(['departure_id', 'price_inr'], 'hotel_categories_departure_id_price_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->dropIndex('inclusions_departure_id_index');
        });

        Schema::table('departure_destinations', function (Blueprint $table) {
            $table->dropIndex('departure_destinations_departure_id_index');
        });

        Schema::table('hotel_categories', function (Blueprint $table) {
            $table->dropIndex('hotel_categories_departure_id_price_index');
        });
    }
};
