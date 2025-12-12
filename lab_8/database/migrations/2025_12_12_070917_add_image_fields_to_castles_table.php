<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('castles', function (Blueprint $table) {
            $table->string('image_original')->nullable()->after('description');
            $table->string('image_thumbnail')->nullable()->after('image_original');
            $table->string('image_preview')->nullable()->after('image_thumbnail');
            $table->string('image_alt')->nullable()->after('image_preview');
        });
    }

    public function down(): void
    {
        Schema::table('castles', function (Blueprint $table) {
            $table->dropColumn(['image_original', 'image_thumbnail', 'image_preview', 'image_alt']);
        });
    }
};