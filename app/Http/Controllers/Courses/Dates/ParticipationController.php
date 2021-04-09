<?php

namespace App\Http\Controllers\Courses\Dates;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\Date;
use App\Models\Courses\Participant;
use App\Models\Courses\Participation;
use App\Models\Partners\Partner;
use App\Models\Receipts\Invoice;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    protected $baseViewPath = 'date.participation';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Date $date)
    {
        if ($request->wantsJson()) {
            return $date->participations()
                ->with([
                    'participant.partner',
                ])
                ->get();
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
    public function store(Request $request, Date $date)
    {
        $attributes = $request->validate([
            'partner_id' => 'required|integer|exists:partners,id',
        ]);

        $date->load([
            'course.item'
        ]);

        $participant = Participant::firstOrCreate([
            'course_id' => $date->course->id,
            'partner_id' => $attributes['partner_id'],
        ]);

        $participation = Participation::firstWhere([
            'course_date_id' => $date->id,
            'participant_id' => $participant->id,
        ]);

        if (is_null($participation)) {
            $participation = Participation::create([
                'course_date_id' => $date->id,
                'participant_id' => $participant->id,
            ]);
        }
        else {
            $partner = Partner::find($attributes['partner_id']);
            $attributes['address'] = $partner->billing_address;
            $invoice = Invoice::create($attributes);
            $invoice->addLine($date->course->item, [
                'quantity' => 10,
            ]);
            $invoice->cache();
        }

        $participant->cache($date->course->item->is_subscription)
            ->save();

        return $participation->load([
            'participant.partner',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function show(Date $date, Participation $participation)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $participation)
            ->with('parent', $date);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function edit(Date $date, Participation $participation)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $participation)
            ->with('parent', $date)
            ->with('partners', Partner::staff()->whereNotNull('user_id')->orderBy('firstname', 'ASC')->orderBy('lastname', 'ASC')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Date $date, Participation $participation)
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
    public function destroy(Request $request, Date $date, Participation $participation)
    {
        if ($isDeletable = $participation->isDeletable()) {
            $participation->delete();
            $participation->participant
                ->cache()
                ->save();
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
