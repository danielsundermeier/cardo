<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Courses\Participant;
use App\Models\Courses\Participation;
use App\Models\Partners\Partner;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Partner $client)
    {
        if ($request->wantsJson()) {
            return $client->corrections()
                ->with([
                    'participant.course',
                ])
                ->get();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $client, Participation $participation)
    {
        return $participation;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Partner $client)
    {
        $attributes = $request->validate([
            'participant_id' => 'required|integer|exists:course_participant,id',
        ]);

        $participant = Participant::with([
            'course.item',
        ])
        ->findOrFail($attributes['participant_id']);

        $participation = Participation::create([
            'course_date_id' => null,
            'participant_id' => $participant->id,
        ]);

        $participant->cache($participant->course->item->is_subscription)
            ->save();

        return $participation->load([
            'participant.course',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Courses\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $client, Participation $participation)
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
    }
}
