<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use App\User;
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
            ->with('model', $staff->load(['courses']))
            ->with('base_view_path', $this->baseViewPath);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $staff)
    {
        return view('partner.edit')
            ->with('model', $staff)
            ->with('base_view_path', $this->baseViewPath)
            ->with('title', 'Personal')
            ->with('users', User::orderBy('name', 'ASC')->get());
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
            'address' => 'nullable|string|max:255',
            'bankname' => 'nullable|string|max:255',
            'bic' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'email' => 'nullable|string||max:255',
            'faxnumber' => 'nullable|string|max:255',
            'firstname' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'mobilenumber' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:255',
            'user_id' => 'nullable|integer',
            'website' => 'nullable|string|max:255',
        ]);

        $attributes['is_client'] = $request->has('is_client');
        $attributes['is_staff'] = $request->has('is_staff');
        $attributes['is_supplier'] = $request->has('is_supplier');

        $staff->update($attributes);

        if ($request->wantsJson()) {
            return $staff;
        }

        return redirect(route($this->baseViewPath . '.show', [$this->baseViewPath => $staff->id]))
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
