<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthdatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healthdatas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');

            $table->dateTime('at');
            $table->unsignedMediumInteger('weight_in_g')->default(0);
            $table->unsignedDecimal('bmi', 4, 2)->nullable();
            $table->unsignedSmallInteger('bloodpresure_systolic')->default(0);
            $table->unsignedSmallInteger('bloodpresure_diastolic')->default(0);
            $table->unsignedSmallInteger('heart_rate')->default(0);
            $table->unsignedSmallInteger('resting_heart_rate')->default(0);

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
        Schema::dropIfExists('healthdatas');
    }
}
