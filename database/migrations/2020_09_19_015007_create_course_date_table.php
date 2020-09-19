<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_date', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('staff_id');

            $table->date('at');
            $table->boolean('is_running')->default(false);

            $table->unsignedSmallInteger('clients_count')->default(0);

            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('staff_id')->references('id')->on('partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_date');
    }
}
