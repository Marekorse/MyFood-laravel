<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Recipes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('title')->unique();
            $table->integer('time');
            $table->integer('chunk');
            $table->string('difficulty');
            $table->string('ingredients',500);
            $table->longText('text');
            $table->longText('approach');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->integer('confirmation')->nullable();
            $table->string('guest')->nullable();
        });
        Schema::table('recipes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}
