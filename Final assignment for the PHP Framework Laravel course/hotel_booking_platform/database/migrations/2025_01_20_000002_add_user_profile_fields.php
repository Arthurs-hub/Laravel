<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 50)->nullable()->after('full_name');
            $table->string('last_name', 50)->nullable()->after('first_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->string('country', 100)->nullable()->after('gender');
            $table->string('city', 100)->nullable()->after('country');
            $table->string('address', 255)->nullable()->after('city');
            $table->string('postal_code', 20)->nullable()->after('address');
            $table->string('passport_number', 50)->nullable()->after('postal_code');
            $table->boolean('two_factor_enabled')->default(false)->after('password');
            $table->enum('two_factor_method', ['email', 'google_authenticator'])->nullable()->after('two_factor_enabled');
            $table->string('two_factor_secret')->nullable()->after('two_factor_method');
            $table->string('two_factor_code')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
            $table->string('email_verification_token')->nullable()->after('two_factor_expires_at');
            $table->text('two_factor_recovery_codes')->nullable()->after('email_verification_token');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'date_of_birth', 'gender',
                'country', 'city', 'address', 'postal_code', 'passport_number',
                'two_factor_enabled', 'two_factor_method', 'two_factor_secret', 'two_factor_code',
                'two_factor_expires_at', 'email_verification_token',
                'two_factor_recovery_codes', 'two_factor_confirmed_at'
            ]);
        });
    }
};