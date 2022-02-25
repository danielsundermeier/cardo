<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\Participant;
use App\Models\Partners\Partner;
use App\Models\Receipts\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Partners\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Partner $partner)
    {
        if ($request->wantsJson()) {
            return $partner->participants()
                ->with('course')
                ->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Partners\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function create(Partner $partner)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partners\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Partner $partner)
    {
        $attributes = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'create_invoice' => 'nullable|boolean',
        ]);

        $course = Course::with(['item'])->findOrFail($attributes['course_id']);

        if (Arr::get($attributes, 'create_invoice', false)) {
            $invoice = Invoice::create([
                'partner_id' => $partner->id,
            ]);
            $invoice->addLine($course->item, [
                'quantity' => 10,
            ]);
            $invoice->cache();
        }

        $participant = Participant::firstOrCreate([
            'course_id' => $course->id,
            'partner_id' => $partner->id,
        ]);
        $participant->cache();

        return $participant->load([
            'course',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $partner
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner, Participant $participant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $partner
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner, Participant $participant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partners\Partner  $partner
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner, Participant $participant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partners\Partner  $partner
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $partner, Participant $participant)
    {
        if ($isDeletable = $participant->isDeletable()) {
            $participant->delete();
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
