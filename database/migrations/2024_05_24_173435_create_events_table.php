<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->foreignId('required_category_id')->constrained('categories', 'category_id');
            $table->unsignedSmallInteger('age_from');
            $table->unsignedSmallInteger('age_to');
            $table->string('name', 100);
            $table->text('description', 500)->nullable();
            $table->date('date');
            $table->time('start_hour');
            $table->unsignedSmallInteger('max_participants');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}