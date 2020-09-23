<?php

namespace App\Http\Controllers;

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
        $user = auth()->user();
        $categories = Category::with([
            'tasks' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('is_completed', false)
                    ->orderBy('priority', 'ASC')
                    ->orderBy('name', 'ASC');
            },
        ])
            ->whereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('is_completed', false);
            })
            ->orderBy('name', 'ASC')
            ->get();

        return view('home')
            ->with('user', $user->load([
                'partner.courses',
            ]))
            ->with('categories', $categories);
    }
}
