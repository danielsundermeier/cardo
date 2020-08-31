<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $baseViewPath = 'staff';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Partner::staff()->with([

                ])
                ->orderBy('firstname', 'ASC')
                ->orderBy('lastname', 'ASC')
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
        return Partner::create([
            'firstname' => 'Neuer',
            'lastname' => 'Mitarbeiter',
            'is_staff' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $staff)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $staff);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $staff)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $staff);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partners\Partner  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $staff)
    {
        $attributes = $request->validate([
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
        ]);

        $staff->update($attributes);

        if ($request->wantsJson()) {
            return $staff;
        }

        return redirect($staff->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partners\Partner  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $staff)
    {
        if ($isDeletable = $staff->isDeletable()) {
            $staff->delete();
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
