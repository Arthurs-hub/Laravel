<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bookings', 'adults')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->integer('adults')->default(1)->after('days');
                $table->integer('children')->default(0)->after('adults');
            });
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['adults', 'children']);
        });
    }
};