<?php

namespace App\Http\Controllers\WorkingTimes;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use App\Models\WorkingTimes\WorkingTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WorkingTimeController extends Controller
{
    protected $baseViewPath = 'workingtime';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return WorkingTime::with([
                'date.course',
                'partner',
            ])->partner($request->input('staff_id'))
            ->date($request->input('date'))
            ->month($request->input('month'))
            ->year($request->input('year'))
            ->orderBy('start_at', 'DESC')
            ->get();
        }

        return view($this->baseViewPath . '.index')
            ->with('partners', Partner::staff()->orderByName()->get())
            ->with('selected_staff_id', $request->user()->id)
            ->with('years', WorkingTime::years())
            ->with('months', WorkingTime::MONTHS);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'staff_id' => 'required|integer|exists:partners,id',
            'industry_hours_formatted' => 'required|formatted_number',
            'start_at_formatted' => 'required|date_format:"d.m.Y H:i"'
        ]);

        $end_at = Carbon::createFromFormat('d.m.Y H:i', $attributes['start_at_formatted']);
        $industry_hours = str_replace(',', '.', $attributes['industry_hours_formatted']);
        $duration_in_seconds = WorkingTime::industryHoursToSeconds($industry_hours);
        $end_at->addSeconds($duration_in_seconds);

        $attributes['end_at'] = $end_at;
        $attributes['duration_break_in_seconds'] = 0;
        Arr::forget($attributes, 'industry_hours_formatted');

        return WorkingTime::create($attributes)->load([
            'date.course',
            'partner',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkingTimes\WorkingTime  $workingtime
     * @return \Illuminate\Http\Response
     */
    public function show(WorkingTime $workingtime)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $workingtime);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkingTimes\WorkingTime  $workingtime
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkingTime $workingtime)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $workingtime);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkingTimes\WorkingTime  $workingtime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkingTime $workingtime)
    {
        $attributes = $request->validate([
            'staff_id' => 'required|integer|exists:partners,id',
            'break_industry_hours_formatted' => 'required|formatted_number',
            'start_at_formatted' => 'required|date_format:"d.m.Y H:i"',
            'end_at_formatted' => 'nullable|date_format:"d.m.Y H:i"'
        ]);

        $workingtime->update($attributes);

        if ($request->wantsJson()) {
            return $workingtime->load([
                'date.course',
                'partner',
            ]);
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkingTimes\WorkingTime  $workingtime
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, WorkingTime $workingtime)
    {
        if ($isDeletable = $workingtime->isDeletable()) {
            $workingtime->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => 'gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => 'kann nicht gelöscht werden.',
            ];
        }

        return redirect(route($this->baseViewPath . '.index'))
            ->with('status', $status);
    }
}
