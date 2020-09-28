<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partners\History;
use App\Models\Partners\Partner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $baseViewPath = 'partner.history';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Partner $client)
    {
        if ($request->wantsJson()) {
            return $client->histories()->with([
                'healthdata'
            ])->orderBy('at', 'DESC')
            ->paginate();
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
    public function store(Request $request, Partner $client)
    {
        return $client->histories()->create([
            'at' => now(),
            'data' => History::defaultData(),
        ])->load([
            'healthdata',
            'partner',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\History  $history
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $client, History $history)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $history->load([
                'partner',
                'healthdata',
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\History  $history
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $client, History $history)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $history);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partners\History  $history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $client, History $history)
    {
        $attributes = collect($request->validate([
            'at_formatted' => 'date_format:d.m.Y',
            'weight_in_kg' => 'required|numeric',
            'bloodpresure_systolic' => 'required|integer',
            'bloodpresure_diastolic' => 'required|integer',
            'heart_rate' => 'required|integer',
            'resting_heart_rate' => 'required|integer',
            'data' => 'required|array',
        ]));

        $history->update($attributes->only([
            'at_formatted',
            'data',
        ])->toArray());

        $healthdataAttributes = $attributes->except([
            'data',
            'at_formatted',
            'weight_in_kg',
        ])->toArray();
        $healthdataAttributes['at'] = Carbon::createFromFormat('d.m.Y', $attributes['at_formatted'])->startOfDay();
        $healthdataAttributes['bmi'] = $client->calculateBmi($attributes['weight_in_kg']);
        $healthdataAttributes['weight_in_g'] = $attributes['weight_in_kg'] * 1000;

        $history->healthdata()->update($healthdataAttributes);

        if ($request->wantsJson()) {
            return $history;
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
     * @param  \App\Models\Partners\History  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $client, History $history)
    {
        if ($isDeletable = $history->isDeletable()) {
            $history->delete();
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
