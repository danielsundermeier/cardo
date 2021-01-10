<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Partners\Partner;
use App\User;
use Carbon\Carbon;
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
            $partners = Partner::client()->with([
                    'participants.course'
                ])
                ->search($request->input('searchtext'))
                ->isActive($request->input('is_active'))
                ->orderBy('firstname', 'ASC')
                ->orderBy('lastname', 'ASC')
                ->paginate();

            foreach ($partners as $key => $partner) {
                $partner->courses_string = $partner->participants->implode('course.link', ', ');
            }

            return $partners;
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
            ->with('model', $client->load([
                'participants.course',
            ]))
            ->with('base_view_path', $this->baseViewPath)
            ->with('courses', Course::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $client)
    {
        return view('partner.edit')
            ->with('model', $client)
            ->with('base_view_path', $this->baseViewPath)
            ->with('title', 'Kunden')
            ->with('users', User::orderBy('name', 'ASC')->get());
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
            'number' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:255',
            'user_id' => 'nullable|integer',
            'website' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'birthday_formatted' => 'nullable|date_format:d.m.Y',
            'height_in_cm' => 'nullable|integer',
            'medical_conditions' => 'nullable|string',
        ]);

        $attributes['birthday_at'] = is_null($attributes['birthday_formatted']) ? null : Carbon::createFromFormat('d.m.Y', $attributes['birthday_formatted'])->startOfDay();
        $attributes['is_active'] = $request->has('is_active');
        $attributes['is_client'] = $request->has('is_client');
        $attributes['is_staff'] = $request->has('is_staff');
        $attributes['is_supplier'] = $request->has('is_supplier');

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
