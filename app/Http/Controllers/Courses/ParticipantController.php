<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\Participant;
use App\Models\Items\Item;
use App\Models\Partners\Partner;
use App\Models\Receipts\Invoice;
use App\Models\Receipts\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ParticipantController extends Controller
{
    protected $baseViewPath = 'course.participant';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Course $course)
    {
        if ($request->wantsJson()) {
            return $course->participants()
                ->with([
                    'partner'
                ])
                ->isActive($request->input('is_active'))
                ->get();
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
    public function store(Request $request, Course $course)
    {
        $attributes = $request->validate([
            'partner_id' => 'required|integer|exists:partners,id',
            'create_invoice' => 'nullable|boolean',
        ]);

        if (Arr::get($attributes, 'create_invoice', false)) {
            $partner = Partner::find($attributes['partner_id']);
            $attributes['address'] = $partner->billing_address;
            $invoice = Invoice::create($attributes);
            $invoice->addLine($course->item, [
                'quantity' => 10,
            ]);
            $invoice->cache();
        }

        $participant = Participant::firstOrCreate([
            'course_id' => $course->id,
            'partner_id' => $attributes['partner_id'],
        ]);
        $participant->cache()
            ->save();

        return $participant->load([
            'partner',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function show(Participant $participant)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $participant);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function edit(Participant $participant)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $participant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Participant $participant)
    {
        $attributes = $request->validate([

        ]);

        $participant->update($attributes);

        if ($request->wantsJson()) {
            return $participant;
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
