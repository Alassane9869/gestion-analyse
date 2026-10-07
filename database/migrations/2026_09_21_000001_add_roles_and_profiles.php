<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('patient')->after('email');
            });
        }

        if (Schema::hasTable('patients')) {
            Schema::table('patients', function (Blueprint $table) {
                if (! Schema::hasColumn('patients', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->unique()->after('id')->constrained('users')->nullOnDelete();
                }
                if (! Schema::hasColumn('patients', 'groupe_sanguin')) {
                    $table->string('groupe_sanguin', 5)->nullable()->after('sexe');
                }
            });
        }

        if (Schema::hasTable('medecins')) {
            Schema::table('medecins', function (Blueprint $table) {
                if (! Schema::hasColumn('medecins', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->unique()->after('id')->constrained('users')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('medecins') && Schema::hasColumn('medecins', 'user_id')) {
            Schema::table('medecins', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('patients')) {
            Schema::table('patients', function (Blueprint $table) {
                if (Schema::hasColumn('patients', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
                if (Schema::hasColumn('patients', 'groupe_sanguin')) {
                    $table->dropColumn('groupe_sanguin');
                }
            });
        }

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
        }
    }
};
