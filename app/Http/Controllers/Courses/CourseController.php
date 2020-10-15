<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Items\Unit;
use App\Models\Partners\Partner;
use App\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $baseViewPath = 'course';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Course::with([
                    'instructor',
                    'item',
                ])
                ->orderByDay()
                ->orderBy('time', 'ASC')
                ->paginate();
        }

        return view($this->baseViewPath . '.index');
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
        $course = Course::create($request->validate([
            'name' => 'required|string',
        ]) + [
            'day' => 0,
        ]);

        $course->item()->create([
            'name' => $course->name,
            'unit_id' => Unit::first()->id,
        ]);

        return $course->load([
            'item'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $course->load([
                'instructor',
            ]))
            ->with('partners', Partner::client()->orderBy('firstname', 'ASC')->orderBy('lastname', 'ASC')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $course->load(['item']))
            ->with('days', Course::DAYS)
            ->with('partners', Partner::staff()->orderBy('firstname', 'ASC')->orderBy('lastname', 'ASC')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'partner_id' => 'required|integer|exists:partners,id',
            'day' => 'required|integer',
            'time_formatted' => 'required|date_format:"H:i"',
        ]);

        $course->update($attributes);

        if ($request->wantsJson()) {
            return $course;
        }

        return redirect($course->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course)
    {
        if ($isDeletable = $course->isDeletable()) {
            $course->delete();
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
