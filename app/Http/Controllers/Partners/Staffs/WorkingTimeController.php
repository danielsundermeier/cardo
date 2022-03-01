<?php

namespace App\Http\Controllers\Partners\Staffs;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use App\Models\WorkingTimes\WorkingTime;
use Illuminate\Http\Request;

class WorkingTimeController extends Controller
{
    protected $baseViewPath = 'staff.workingtime';

    public function store(Request $request, Partner $staff)
    {
        $user = auth()->user();

        $attributes = [
            'start_at' => now(),
            'duration_in_seconds' => 0,
            'duration_break_in_seconds' => 0,
        ];

        return $user->partner->workingtimes()->create($attributes)->load([
            'date.course',
            'partner',
        ])->append([
            'running_industry_hours',
            'running_industry_hours_formatted',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Partner $staff)
    {
        if ($request->wantsJson()) {
            $time = $staff->workingtimes()
                ->with([
                    'date.course',
                    'partner',
                ])
                ->whereNull('end_at')
                ->whereNull('course_date_id')
                ->orderBy('start_at', 'DESC')
                ->first();

            if (! is_null($time)) {
                $time->append([
                    'running_industry_hours',
                    'running_industry_hours_formatted',
                ]);
            }

            return $time;
        }

        if (! $request->hasValidSignature()) {
            abort(401);
        }

        if (! is_null($staff->user)) {
            auth()->login($staff->user);
        }

        return view($this->baseViewPath . '.show')
            ->with('model', $staff);
    }

    public function destroy(Request $request, Partner $staff, Workingtime $time)
    {
        $attributes = $request->validate([
            'duration_break_in_seconds' => 'required|numeric',
        ]);

        $end_at = now();

        $time->update([
            'end_at' => $end_at,
            'duration_break_in_seconds' => $attributes['duration_break_in_seconds'],
        ]);
    }
}
