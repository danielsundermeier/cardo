<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseParticipationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_participation', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_date_id');
            $table->unsignedBigInteger('participant_id');

            $table->timestamps();

            $table->foreign('course_date_id')->references('id')->on('course_date');
            $table->foreign('participant_id')->references('id')->on('course_participant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_participation');
    }
}
