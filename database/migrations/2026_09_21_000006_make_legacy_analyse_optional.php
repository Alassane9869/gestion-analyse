<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            $table->dropForeign(['analyse_id']);
        });

        Schema::table('resultats', function (Blueprint $table) {
            $table->foreignId('analyse_id')->nullable()->change();
            $table->foreign('analyse_id')->references('id')->on('analyses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            $table->dropForeign(['analyse_id']);
        });

        Schema::table('resultats', function (Blueprint $table) {
            $table->foreignId('analyse_id')->nullable(false)->change();
            $table->foreign('analyse_id')->references('id')->on('analyses')->cascadeOnDelete();
        });
    }
};
