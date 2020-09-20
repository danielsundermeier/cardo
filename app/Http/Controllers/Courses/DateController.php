<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\Date;
use App\Models\Partners\Partner;
use Illuminate\Http\Request;

class DateController extends Controller
{
    protected $baseViewPath = 'course.date';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Course $course)
    {
        if ($request->wantsJson()) {
            return $course->dates()
            ->with([
                'instructor',
            ])
            ->orderBy('at', 'DESC')
            ->paginate();
        }
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
    public function store(Request $request, Course $course)
    {
        $attributes = $request->validate([
            'at_formatted' => 'required|date_format:"d.m.Y"'
        ]);

        $attributes['staff_id'] = $course->partner_id;

        return $course->dates()
            ->create($attributes)
            ->load([
                'instructor',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course, Date $date)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $date)
            ->with('parent', $course->load([
                'item',
            ]))
            ->with('last_date', Date::withCount('participations')->where('id', '!=', $date->id)->where('course_id', $date->course_id)->where('at', '<', $date->at)->latest()->first())
            ->with('partners', Partner::client()->orderBy('firstname', 'ASC')->orderBy('lastname', 'ASC')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Date $date)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $date)
            ->with('parent', $course)
            ->with('partners', Partner::staff()->whereNotNull('user_id')->orderBy('firstname', 'ASC')->orderBy('lastname', 'ASC')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, Date $date)
    {
        $attributes = $request->validate([
            'at_formatted' => 'required|date_format:"d.m.Y"',
            'staff_id' => 'required|integer|exists:partners,id',
        ]);

        $date->update($attributes);

        if ($request->wantsJson()) {
            return $date;
        }

        return redirect($date->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course, Date $date)
    {
        if ($isDeletable = $date->isDeletable()) {
            $date->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => 'Datensatz gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => 'Datensatz kann nicht gelöscht werden.',
            ];
        }

        return redirect($course->path)
            ->with('status', $status);
    }
}
