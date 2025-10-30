<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Поле уже существует в базе данных
        if (!Schema::hasColumn('hotels', 'manager_id')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('rating');
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn('manager_id');
        });
    }
};