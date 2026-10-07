<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['patients', 'analyses'] as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'laboratoire_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['laboratoire_id']);
                $table->dropColumn('laboratoire_id');
            });
        }

        Schema::dropIfExists('laboratoires');
    }

    public function down(): void
    {
        // La suppression des laboratoires est définitive.
    }
};
