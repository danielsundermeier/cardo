<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Courses\Participant;
use App\Models\Courses\Participation;
use App\Models\Items\Item;
use App\Models\Items\Unit;
use App\Models\Partners\Partner;
use App\Models\Receipts\Expense;
use App\Models\Receipts\Receipt;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $baseViewPath = 'expense';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Expense::with([
                'partner'
            ])
                ->partner($request->input('partner_id'))
                ->isPaid($request->input('is_paid'))
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

        $expense = Expense::create([
            'address' => $partner->billing_address,
            'partner_id' => $partner->id,
            'date_due' => now()->add(14, 'days'),
        ]);

        if ($request->wantsJson()) {
            return $expense;
        }

        return redirect($expense->path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipts\Receipt  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $expense)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $expense->load([
                'partner',
                'previewFile',
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipts\Receipt  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $expense)
    {
        $expense->load([
            'previewFile',
        ]);

        return view($this->baseViewPath . '.edit')
            ->with('model', $expense)
            ->with('partners', Partner::client()->get())
            ->with('units', Unit::all())
            ->with('items', Item::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipts\Receipt  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $expense)
    {
        $attributes = $request->validate([
            'address' => 'nullable|string',
            'partner_id' => 'required',
            'name' => 'required|string',
            'date_formatted' => 'date_format:d.m.Y',
        ]);

        $attributes['date'] = Carbon::createFromFormat('d.m.Y', $attributes['date_formatted'])->startOfDay();
        $attributes['date_due'] = Carbon::createFromFormat('d.m.Y', $attributes['date_formatted'])->startOfDay()->addDays(14);

        $expense->update($attributes);
        $expense->cache();

        if ($request->wantsJson()) {
            return $expense;
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
     * @param  \App\Models\Receipts\Receipt  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Receipt $expense)
    {
        if ($isDeletable = $expense->isDeletable()) {
            $expense->load('lines.item');
            foreach ($expense->lines as $key => $line) {
                $line->delete();
                $line->cache();
            }
            $expense->delete();
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
