<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Courses\Participant;
use App\Models\Courses\Participation;
use App\Models\Items\Item;
use App\Models\Receipts\Line;
use App\Models\Receipts\Receipt;
use Illuminate\Http\Request;

class LineController extends Controller
{
    protected $baseViewPath = 'receipt.line';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Receipt $receipt)
    {
        if ($request->wantsJson()) {
            return $receipt->lines()
                ->with([
                    'item',
                    'partner',
                    'unit',
                ])
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
    public function store(Request $request, Receipt $receipt)
    {
        $attributes = $request->validate([
            'item_id' => 'required',
        ]);

        $line = $receipt->addLine(Item::findOrFail($attributes['item_id']));

        $receipt->cache();

        if ($request->wantsJson()) {
            return $line->load([
                'item',
                'unit',
            ]);
        }

        return back()->with('status', 'Artikel hinzugefügt!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function show(Line $line)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function edit(Line $line)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt, Line $line)
    {
        $attributes = $request->validate([
            'description' => 'nullable|string',
            'discount' => 'required|numeric',
            'name' => 'required',
            'quantity' => 'required|numeric',
            'tax' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'unit_id' => 'required|integer|exists:units,id',
            'partner_id' => 'nullable|integer|exists:partners,id'
        ]);

        if ($line->partner_id && $line->partner_id != $attributes['partner_id'] && $line->item->course_id) {
            $participant = Participant::firstWhere([
                'partner_id' => $line->partner_id,
                'course_id' => $line->item->course_id,
            ]);
        }

        $line->update($attributes);
        $receipt->cache();

        if (isset($participant)) {
            $participant->cache($line->item->is_subscription)
                ->save();
        }

        if ($request->wantsJson()) {
            return $line->load([
                'item',
                'partner',
                'unit',
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
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Receipt $receipt, Line $line)
    {
        if ($isDeletable = $line->isDeletable()) {
            $line->delete();
            $line->cache();
            $receipt->cache();
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
