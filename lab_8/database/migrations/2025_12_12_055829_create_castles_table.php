<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('castles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название замка
            $table->string('century_founded'); // Век основания (например: "14 век")
            $table->integer('year_founded')->nullable(); // Конкретный год основания (если известен)
            $table->string('location'); // Месторасположение
            $table->string('affiliation'); // Принадлежность (Ливонский орден и т.д.)
            $table->string('owner'); // Владелец замка
            $table->text('description')->nullable(); // Подробное описание
            $table->string('image_filename'); // Имя файла изображения
            $table->string('slug')->unique(); // Уникальный slug для URL (vastseliina, vasknarva и т.д.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('castles');
    }
};
