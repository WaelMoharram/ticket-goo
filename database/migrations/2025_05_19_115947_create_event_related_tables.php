<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stadiums', function (Blueprint $table) {
            $table->id();
            $table->string('football_ticket_net_id')->unique();
            $table->json('name');
            $table->json('city');
            $table->json('country');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('football_ticket_net_id')->unique();
            $table->json('name');
            $table->json('nice_name')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('football_ticket_net_id')->unique();
            $table->json('name');
            $table->json('nice_name')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('football_ticket_net_id')->unique();
            $table->json('name');
            $table->string('date')->nullable();
            $table->string('full_date')->nullable();
            $table->string('link')->nullable();
            $table->string('currency_code')->nullable();

            $table->foreignId('stadium_id')->constrained('stadiums');
            $table->foreignId('league_id')->constrained('leagues');
            $table->foreignId('team1_id')->constrained('teams');
            $table->foreignId('team2_id')->constrained('teams');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('leagues');
        Schema::dropIfExists('stadiums');
    }
};
