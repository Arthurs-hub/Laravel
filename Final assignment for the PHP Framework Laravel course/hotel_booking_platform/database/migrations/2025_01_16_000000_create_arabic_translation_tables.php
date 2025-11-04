<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hotel Arabic Descriptions
        Schema::create('hotel_arabic_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('english_title')->unique();
            $table->text('arabic_description');
            $table->timestamps();
        });

        // Hotel Arabic Addresses
        Schema::create('hotel_arabic_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('english_title')->unique();
            $table->string('arabic_address', 500);
            $table->timestamps();
        });

        // Hotel Translations
        Schema::create('hotel_translations', function (Blueprint $table) {
            $table->id();
            $table->string('original_title')->unique();
            $table->string('arabic_title');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_arabic_descriptions');
        Schema::dropIfExists('hotel_arabic_addresses');
        Schema::dropIfExists('hotel_translations');
    }
};