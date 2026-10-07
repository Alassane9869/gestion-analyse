<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('commande_type_analyse') && ! Schema::hasColumn('commande_type_analyse', 'statut')) {
            Schema::table('commande_type_analyse', function (Blueprint $table) {
                $table->string('statut')->default('en_attente');
                $table->index('statut');
            });
        }

        if (Schema::hasTable('resultats') && ! Schema::hasColumn('resultats', 'commande_type_analyse_id')) {
            Schema::table('resultats', function (Blueprint $table) {
                $table->foreignId('commande_type_analyse_id')
                    ->nullable()
                    ->after('type_analyse_id')
                    ->constrained('commande_type_analyse')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('resultats') && Schema::hasColumn('resultats', 'commande_type_analyse_id')) {
            Schema::table('resultats', function (Blueprint $table) {
                $table->dropForeign(['commande_type_analyse_id']);
                $table->dropColumn('commande_type_analyse_id');
            });
        }

        if (Schema::hasTable('commande_type_analyse') && Schema::hasColumn('commande_type_analyse', 'statut')) {
            Schema::table('commande_type_analyse', function (Blueprint $table) {
                $table->dropIndex(['statut']);
                $table->dropColumn('statut');
            });
        }
    }
};