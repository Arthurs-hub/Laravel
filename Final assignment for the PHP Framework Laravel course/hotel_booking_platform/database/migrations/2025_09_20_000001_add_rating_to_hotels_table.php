<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (!Schema::hasColumn('hotels', 'rating')) {
                    $table->decimal('rating', 2, 1)->default(4.5)->after('country');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('hotels') && Schema::hasColumn('hotels', 'rating')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropColumn('rating');
            });
        }
    }
};