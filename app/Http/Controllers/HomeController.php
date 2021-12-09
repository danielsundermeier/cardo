<?php

namespace App\Http\Controllers;

use App\Models\Courses\Date;
use App\Models\Partners\Partner;
use App\Models\Tasks\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $upcoming_birthdays_days = 30;

        $user = auth()->user()->load([
            'partner'
        ]);
        if ($user->partner) {
            $categories = Category::with([
                'tasks' => function ($query) use ($user) {
                    $query->partner($user->partner->id)
                        ->where('is_completed', false)
                        ->orderBy('priority', 'ASC')
                        ->orderBy('name', 'ASC');
                },
            ])
                ->whereHas('tasks', function ($query) use ($user) {
                    $query->partner($user->partner->id)
                        ->where('is_completed', false);
                })
                ->orderBy('name', 'ASC')
                ->get();
            $upcoming_dates = Date::upcomingFor($user->partner);
        }
        else {
            $categories = new \Illuminate\Database\Eloquent\Collection();
            $upcoming_dates = new \Illuminate\Database\Eloquent\Collection();
        }

        return view('home')
            ->with('user', $user)
            ->with('upcoming_dates', $upcoming_dates)
            ->with('upcoming_birthdays', Partner::upcomingBirthdays($upcoming_birthdays_days)->orderByRaw('DATE_ADD(birthday_at, INTERVAL YEAR(CURDATE())-YEAR(birthday_at) YEAR) DESC')->orderByName()->get())
            ->with('categories', $categories);
    }
}
