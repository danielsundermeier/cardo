<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('course_id')->nullable();

            $table->string('name');

            $table->decimal('unit_price', 15, 6)->default(0);

            $table->unsignedMediumInteger('revenue_account_number')->default(0);
            $table->unsignedMediumInteger('expense_account_number')->default(0);

            $table->unsignedSmallInteger('course_count')->nullable();

            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('course_id')->references('id')->on('courses');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
