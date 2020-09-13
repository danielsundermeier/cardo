<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use App\Partners\Healthdata;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HealthdataController extends Controller
{
    protected $baseViewPath = 'partner.healthdatas';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Partner $partner)
    {
        if ($request->wantsJson()) {
            return $partner->healthdatas()
                ->orderBy('at', 'DESC')
                ->get();
        }

        return view($this->baseViewPath . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Partner $partner)
    {
        return $partner->healthdatas()->create([
            'at' => now()->startOfDay(),
            'weight_in_g' => 0,
            'bmi' => 0,
            'bloodpresure_systolic' => 0,
            'bloodpresure_diastolic' => 0,
            'heart_rate' => 0,
            'resting_heart_rate' => 0,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Partners\Healthdata  $healthdata
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner, Healthdata $healthdata)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $healthdata);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Partners\Healthdata  $healthdata
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner, Healthdata $healthdata)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $healthdata);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partners\Healthdata  $healthdata
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner, Healthdata $healthdata)
    {
        $attributes = $request->validate([
            'at_formatted' => 'date_format:d.m.Y',
            'weight_in_kg' => 'required|numeric',
            'bloodpresure_systolic' => 'required|integer',
            'bloodpresure_diastolic' => 'required|integer',
            'heart_rate' => 'required|integer',
            'resting_heart_rate' => 'required|integer',
        ]);

        $attributes['at'] = Carbon::createFromFormat('d.m.Y', $attributes['at_formatted'])->startOfDay();
        $attributes['bmi'] = $partner->calculateBmi($attributes['weight_in_kg']);
        $attributes['weight_in_g'] = $attributes['weight_in_kg'] * 1000;

        $healthdata->update($attributes);

        if ($request->wantsJson()) {
            return $healthdata;
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
     * @param  \App\Partners\Healthdata  $healthdata
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $partner, Healthdata $healthdata)
    {
        if ($isDeletable = $healthdata->isDeletable()) {
            $healthdata->delete();
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
