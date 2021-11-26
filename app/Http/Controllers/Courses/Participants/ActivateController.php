<?php

namespace App\Http\Controllers\Courses\Participants;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\Participant;
use App\Models\Items\Item;
use App\Models\Partners\Partner;
use App\Models\Receipts\Invoice;
use App\Models\Receipts\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ActivateController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, Participant $participant)
    {
        $attributes = $request->validate([

        ]);

        $participant->update([
            'is_active' => true,
        ]);

        if ($request->wantsJson()) {
            return $participant->load([
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
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course, Participant $participant)
    {
        $attributes = $request->validate([

        ]);

        $participant->update([
            'is_active' => false,
        ]);

        if ($request->wantsJson()) {
            return $participant->load([
                'partner',
            ]);
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }
}
