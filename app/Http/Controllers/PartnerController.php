<?php

namespace App\Http\Controllers;

use App\Models\Partners\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    protected $baseViewPath = '{{ modelVariable }}';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $partner);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $partner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partners\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        $attributes = $request->validate([

        ]);

        $partner->update($attributes);

        if ($request->wantsJson()) {
            return $partner;
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
     * @param  \App\Models\Partners\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $partner)
    {
        if ($isDeletable = $partner->isDeletable()) {
            $partner->delete();
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
