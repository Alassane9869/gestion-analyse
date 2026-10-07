<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('commande_analyses')) {
            Schema::create('commande_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('total', 10, 2)->default(0);
            $table->string('statut')->default('en_attente');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('commande_type_analyse')) {
            Schema::create('commande_type_analyse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_analyse_id')->constrained('commande_analyses')->cascadeOnDelete();
            $table->foreignId('type_analyse_id')->constrained('type_analyses')->cascadeOnDelete();
            $table->decimal('prix_unitaire', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['commande_analyse_id', 'type_analyse_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_type_analyse');
        Schema::dropIfExists('commande_analyses');
    }
};
