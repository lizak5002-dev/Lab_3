<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('castles', function (Blueprint $table) {
            $table->string('image_filename')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('castles', function (Blueprint $table) {
            $table->string('image_filename')->nullable(false)->change();
        });
    }
};