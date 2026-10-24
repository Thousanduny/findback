<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Автор
            $table->foreignId('category_id')->constrained(); // Категория
            $table->foreignId('location_id')->constrained(); // Локация
            $table->string('title'); // Название
            $table->text('description')->nullable(); // Описание
            $table->string('photo')->nullable(); // Путь к фото
            $table->enum('type', ['lost', 'found']); // Потерял или Нашел
            $table->enum('status', ['active', 'returned'])->default('active'); // Статус
            $table->dateTime('event_date'); // Дата события
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
