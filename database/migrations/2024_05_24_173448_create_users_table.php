<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('email', 120)->unique();
            $table->string('password', 100);
            $table->date('birthdate');
            $table->smallInteger('points')->nullable();
            $table->string('phone', 11);
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->foreign('sport_id')->references('sport_id')->on('sports')->onDelete('restrict');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('restrict');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('restrict');
            $table->string('photo')->nullable();
            $table->boolean('approved')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
