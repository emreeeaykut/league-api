<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();

            $table->tinyInteger('home_team_result')->default(0);
            $table->tinyInteger('away_team_result')->default(0);
            $table->tinyInteger('is_over')->default(0);
            $table->integer('rank')->nullable();

            $table->foreignId('home_team_id')->constrained('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('away_team_id')->constrained('teams')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
