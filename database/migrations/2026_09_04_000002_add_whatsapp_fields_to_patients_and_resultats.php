<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('whatsapp_phone', 30)->nullable()->after('telephone');
            $table->boolean('whatsapp_opt_in')->default(false)->after('whatsapp_phone');
            $table->dateTime('whatsapp_opt_in_at')->nullable()->after('whatsapp_opt_in');
        });

        Schema::table('resultats', function (Blueprint $table) {
            $table->dateTime('whatsapp_sent_at')->nullable()->after('remarques');
            $table->string('whatsapp_message_id')->nullable()->after('whatsapp_sent_at');
            $table->text('whatsapp_error')->nullable()->after('whatsapp_message_id');
        });
    }

    public function down(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_sent_at', 'whatsapp_message_id', 'whatsapp_error']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_phone', 'whatsapp_opt_in', 'whatsapp_opt_in_at']);
        });
    }
};