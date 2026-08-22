<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Removes duplicate POI -> departure links and stops new ones being created.
 *
 * The same (reference_id, departure_id) pair was stored up to three times,
 * which inflated the "N Tours" counts on POI cards and multiplied the related
 * -attraction grids. The application code no longer trusts the table to be
 * unique, but nothing in THIS codebase writes to it - the rows come from an
 * external importer - so the only place the rule can actually be enforced is
 * the database.
 *
 * READ BEFORE RUNNING - two things this does that cannot be undone by down():
 *
 *   1. It DELETES rows (136 at the time of writing), keeping the lowest id of
 *      each duplicate pair. down() drops the constraint but cannot bring the
 *      deleted rows back. Take a backup / export the table first.
 *
 *   2. Once the unique index exists, the external importer will ERROR on any
 *      plain INSERT of a pair that already exists, where today it silently
 *      creates a duplicate. Confirm that importer uses INSERT IGNORE, REPLACE,
 *      or an upsert before running this, or its next sync will fail.
 *
 * Run it on its own (see the note in the companion index migration):
 *
 *     php artisan migrate --path=database/migrations/2026_08_22_000002_dedupe_and_constrain_poi_departure_links.php
 */
return new class extends Migration
{
    public function up(): void
    {
        // Keep the earliest row of each duplicated pair, drop the rest.
        DB::statement('
            DELETE dupe
            FROM departure_destination_point_of_interests AS dupe
            INNER JOIN departure_destination_point_of_interests AS keeper
                ON dupe.reference_id = keeper.reference_id
               AND dupe.departure_id = keeper.departure_id
               AND dupe.id > keeper.id
        ');

        Schema::table('departure_destination_point_of_interests', function (Blueprint $table) {
            if (! Schema::hasIndex('departure_destination_point_of_interests', 'ddpoi_reference_departure_unique')) {
                $table->unique(['reference_id', 'departure_id'], 'ddpoi_reference_departure_unique');
            }
        });
    }

    public function down(): void
    {
        // Only the constraint is reversible; the deleted duplicate rows are not.
        Schema::table('departure_destination_point_of_interests', function (Blueprint $table) {
            $table->dropUnique('ddpoi_reference_departure_unique');
        });
    }
};
