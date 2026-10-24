<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('country')->nullable()->after('name');
            $table->string('city')->nullable()->after('country');
            $table->string('street')->nullable()->after('city');
            $table->string('details')->nullable()->after('street'); // Для "под деревом возле стены"
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['country', 'city', 'street', 'details']);
        });
    }
};
