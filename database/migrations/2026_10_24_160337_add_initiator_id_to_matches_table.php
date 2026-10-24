<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            // ID пользователя, который инициировал чат (нажал "Связаться")
            $table->foreignId('initiator_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['initiator_id']);
            $table->dropColumn('initiator_id');
        });
    }
};
