<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_participant', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('partner_id');

            $table->boolean('is_active')->default(true);
            $table->boolean('has_flatrate')->default(false);
            $table->boolean('has_subscription')->default(false);

            $table->unsignedMediumInteger('participations_count')->default(0);
            $table->smallInteger('open_participations_count')->default(0);

            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('partner_id')->references('id')->on('partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_participant');
    }
}
