<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Courses\Participant;
use App\Models\Courses\Participation;
use App\Models\Items\Item;
use App\Models\Items\Unit;
use App\Models\Partners\Partner;
use App\Models\Receipts\Invoice;
use App\Models\Receipts\Receipt;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $baseViewPath = 'invoice';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Invoice::with([
                'partner'
            ])
                ->partner($request->input('partner_id'))
                ->isPaid($request->input('is_paid'))
                ->search($request->input('searchtext'))
                ->year($request->input('year'))
                ->orderBy('number', 'DESC')
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
        $partner = $request->has('partner_id') ? Partner::find($request->input('partner_id')) : Partner::client()->first();

        $invoice = Invoice::create([
            'address' => $partner->billing_address,
            'partner_id' => $partner->id,
            'date_due' => now()->add(Invoice::DUE_IN_DAYS, 'days'),
        ]);

        if ($request->wantsJson()) {
            return $invoice;
        }

        return redirect($invoice->path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipts\Receipt  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $invoice)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $invoice->load([
                'partner',
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipts\Receipt  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $invoice)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $invoice)
            ->with('partners', Partner::client()->orderByName()->get())
            ->with('units', Unit::orderBy('name', 'ASC')->get())
            ->with('items', Item::orderByName()->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipts\Receipt  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $invoice)
    {
        $attributes = $request->validate([
            'address' => 'nullable|string',
            'partner_id' => 'required',
            'number' => 'required|integer',
            'date_formatted' => 'date_format:d.m.Y',
        ]);

        $attributes['date'] = Carbon::createFromFormat('d.m.Y', $attributes['date_formatted'])->startOfDay();
        $attributes['date_due'] = Carbon::createFromFormat('d.m.Y', $attributes['date_formatted'])->startOfDay()->addDays(14);

        $invoice->update($attributes);
        $invoice->cache();

        if ($request->wantsJson()) {
            return $invoice;
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
     * @param  \App\Models\Receipts\Receipt  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Receipt $invoice)
    {
        if ($isDeletable = $invoice->isDeletable()) {
            $invoice->load('lines.item');
            foreach ($invoice->lines as $key => $line) {
                $line->delete();
                $line->cache();
            }
            $invoice->delete();
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
