<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();

            $table->tinyInteger('played')->default(0);
            $table->tinyInteger('won')->default(0);
            $table->tinyInteger('drawn')->default(0);
            $table->tinyInteger('lost')->default(0);
            $table->tinyInteger('gf')->default(0);
            $table->tinyInteger('ga')->default(0);
            $table->integer('gd')->default(0);
            $table->tinyInteger('points')->default(0);

            $table->foreignId('team_id')->constrained('teams')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('points');
    }
}
