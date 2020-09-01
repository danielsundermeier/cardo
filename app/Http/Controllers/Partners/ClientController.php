<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $baseViewPath = 'client';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Partner::client()->with([

                ])
                ->orderBy('firstname', 'ASC')
                ->orderBy('lastname', 'ASC')
                ->paginate();
        }

        return view($this->baseViewPath . '.index')
            ->with('base_view_path', $this->baseViewPath);
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
        return Partner::create([
            'firstname' => 'Neuer',
            'lastname' => 'Kunde',
            'is_client' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $client)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $client)
            ->with('base_view_path', $this->baseViewPath);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $client)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $client)
            ->with('base_view_path', $this->baseViewPath);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partners\Partner  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $client)
    {
        $attributes = $request->validate([
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
        ]);

        $client->update($attributes);

        if ($request->wantsJson()) {
            return $client;
        }

        return redirect(route($this->baseViewPath . '.show', [$this->baseViewPath => $client->id]))
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partners\Partner  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $client)
    {
        if ($isDeletable = $client->isDeletable()) {
            $client->delete();
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
