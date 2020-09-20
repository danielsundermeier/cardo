<?php

namespace App\Http\Controllers\Courses\Dates\Participations;

use App\Http\Controllers\Controller;
use App\Models\Courses\Date;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    public function store(Request $request, Date $date)
    {
        $attributes = $request->validate([
            'last_date_id' => 'required|integer|exists:course_date,id',
        ]);

        $last_date = Date::with([
            'participations.participant'
        ])
        ->find($attributes['last_date_id']);

        foreach ($last_date->participations as $key => $last_participation) {
            $participation = $date->participations()->create($last_participation->only([
                'participant_id'
            ]));
            $last_participation->participant
                ->cache()
                ->save();
        }

        return $date->participations()
            ->with([
                'participant.partner',
            ])
            ->get();
    }
}
