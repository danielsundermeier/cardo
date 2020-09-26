<?php

namespace App\Http\Controllers\WorkingTimes;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use App\Models\WorkingTimes\WorkingTime;
use Illuminate\Http\Request;

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
            ->year($request->input('year'))
            ->month($request->input('month'))
            ->orderBy('start_at', 'DESC')
            ->paginate();
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
            'start_at_formatted' => 'required|date_format:"d.m.Y"'
        ]);

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
            'industry_hours_formatted' => 'required|formatted_number',
            'start_at_formatted' => 'required|date_format:"d.m.Y"'
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
