<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('partner_id');

            $table->string('name');
            $table->text('description')->nullable();
            $table->smallInteger('duration_in_seconds')->default(3600);
            $table->smallInteger('day')->default(0);
            $table->dateTime('time')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
