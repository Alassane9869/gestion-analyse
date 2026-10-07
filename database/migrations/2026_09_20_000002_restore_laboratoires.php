<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('laboratoires')) {
            Schema::create('laboratoires', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('adresse')->nullable();
                $table->string('telephone')->nullable();
                $table->string('email')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('analyses') && ! Schema::hasColumn('analyses', 'laboratoire_id')) {
            Schema::table('analyses', function (Blueprint $table) {
                $table->foreignId('laboratoire_id')->nullable()->constrained('laboratoires')->nullOnDelete();
            });
        }

        if (Schema::hasTable('patients') && ! Schema::hasColumn('patients', 'laboratoire_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->foreignId('laboratoire_id')->nullable()->constrained('laboratoires')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('patients') && Schema::hasColumn('patients', 'laboratoire_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->dropForeign(['laboratoire_id']);
                $table->dropColumn('laboratoire_id');
            });
        }

        if (Schema::hasTable('analyses') && Schema::hasColumn('analyses', 'laboratoire_id')) {
            Schema::table('analyses', function (Blueprint $table) {
                $table->dropForeign(['laboratoire_id']);
                $table->dropColumn('laboratoire_id');
            });
        }

        Schema::dropIfExists('laboratoires');
    }
};
