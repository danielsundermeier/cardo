<?php

namespace App\Http\Controllers;

use App\Models\Receipts\Line;
use Illuminate\Http\Request;

class LineController extends Controller
{
    protected $baseViewPath = 'receipt.line';

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
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function show(Line $line)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $line);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function edit(Line $line)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $line);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Line $line)
    {
        $attributes = $request->validate([

        ]);

        $line->update($attributes);

        if ($request->wantsJson()) {
            return $line;
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
     * @param  \App\Models\Receipts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Line $line)
    {
        if ($isDeletable = $line->isDeletable()) {
            $line->delete();
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
