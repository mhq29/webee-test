<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('package_expiry')->nullable();
            $table->boolean('user_verified')->default(0)->nullable();
            $table->integer('role_id')->default(3); //(3 will be for normal users)
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('cinemas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('branch')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('cinema_id');
            $table->foreign('cinema_id')->references('id')->on('cinema')->onDelete('cascade');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
        Schema::create('show_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movie_id');;
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->string('time')->nullable();
            $table->string('seating_capacity')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
        Schema::create('seats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seat_num');
            $table->integer('status'); //1,0 (booked or not)
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('show_times');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
        Schema::create('pricing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('show_times')->onDelete('cascade');
            $table->string('price')->nullable();
            $table->string('type'); //silver, vip
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('show_times');
            $table->timestamps();
        });
        throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
