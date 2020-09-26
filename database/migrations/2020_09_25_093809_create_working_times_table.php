<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('course_date_id')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->unsignedSmallInteger('duration_in_seconds')->default(0);
            $table->boolean('is_paid')->default(false);

            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('course_date_id')->references('id')->on('course_date')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('working_times');
    }
}
