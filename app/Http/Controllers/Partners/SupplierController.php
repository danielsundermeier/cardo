<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
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
        return view($this->baseViewPath . '.edit')
            ->with('model', $supplier)
            ->with('base_view_path', $this->baseViewPath);
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
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
        ]);

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
