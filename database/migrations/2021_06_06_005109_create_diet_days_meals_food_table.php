<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietDaysMealsFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_days_meals_food', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id');
            $table->foreignId('diet_days_meal_id');
            $table->foreignId('diet_food_id');

            $table->unsignedTinyInteger('order_by')->default(0);
            $table->double('amount', 10, 3)->default(0);

            $table->unsignedTinyInteger('rating_points')->default(0);
            $table->text('rating_comment')->nullable();

            $table->double('calories', 10, 3)->default(0);
            $table->double('carbohydrate', 10, 3)->default(0);
            $table->double('fat', 10, 3)->default(0);
            $table->double('protein', 10, 3)->default(0);

            $table->timestamps();

            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('diet_days_meal_id')->references('id')->on('diet_days_meals');
            $table->foreign('diet_food_id')->references('id')->on('diet_food');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diet_days_meals_food');
    }
}
