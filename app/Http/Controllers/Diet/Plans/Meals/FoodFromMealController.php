<?php

namespace App\Http\Controllers\Diet\Plans\Meals;

use App\Http\Controllers\Controller;
use App\Models\Diet\Plans\Meals\Food;
use App\Models\Diet\Plans\Meals\Meal;
use Illuminate\Http\Request;

class FoodFromMealController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Meal $meal)
    {
        $attributes = $request->validate([
            'meal_id' => 'required|exists:diet_meals,id',
        ]);

        $meal->loadCount('foods');

        $foods = \App\Models\Diet\Meals\Food::with([
            'food',
        ])->where('diet_meal_id', $attributes['meal_id'])
        ->orderBy('order_by', 'ASC')
        ->get();

        $meal_foods = [];
        foreach ($foods as $food) {
            $meal_foods[] = $meal->foods()->create([
                'partner_id' => $meal->partner_id,
                'diet_food_id' => $food->diet_food_id,
                'order_by' => $meal->foods_count,
                'amount' => $food->amount,
            ])->load('food');
            $meal->foods_count++;
        }

        return $meal_foods;
    }
}
