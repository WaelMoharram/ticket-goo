<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_code');
            $table->string('phone')->unique();
            $table->boolean('terms_accepted')->default(false);
            $table->boolean('subscribe_to_newsletter')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone_code',
                'phone',
                'terms_accepted',
                'subscribe_to_newsletter',
            ]);
        });
    }
};
