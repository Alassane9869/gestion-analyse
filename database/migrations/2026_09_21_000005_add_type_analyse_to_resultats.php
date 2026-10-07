<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('resultats', 'type_analyse_id')) {
            Schema::table('resultats', function (Blueprint $table) {
                $table->foreignId('type_analyse_id')->nullable()->after('analyse_id')->constrained('type_analyses')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('resultats', 'type_analyse_id')) {
            Schema::table('resultats', function (Blueprint $table) {
                $table->dropForeign(['type_analyse_id']);
                $table->dropColumn('type_analyse_id');
            });
        }
    }
};
