<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use App\User;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $baseViewPath = 'supplier';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Partner::supplier()->with([

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
            'lastname' => 'Lieferant',
            'is_supplier' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $supplier)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $supplier)
            ->with('base_view_path', $this->baseViewPath);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $supplier)
    {
        return view('partner.edit')
            ->with('model', $supplier)
            ->with('base_view_path', $this->baseViewPath)
            ->with('users', User::orderBy('name', 'ASC')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partners\Partner  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $supplier)
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
            'postcode' => 'nullable|string|max:255',
            'user_id' => 'nullable|integer',
            'website' => 'nullable|string|max:255',
        ]);

        $attributes['is_client'] = $request->has('is_client');
        $attributes['is_staff'] = $request->has('is_staff');
        $attributes['is_supplier'] = $request->has('is_supplier');

        $supplier->update($attributes);

        if ($request->wantsJson()) {
            return $supplier;
        }

        return redirect(route($this->baseViewPath . '.show', [$this->baseViewPath => $supplier->id]))
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partners\Partner  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $supplier)
    {
        if ($isDeletable = $supplier->isDeletable()) {
            $supplier->delete();
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
