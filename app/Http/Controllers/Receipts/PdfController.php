<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Receipts\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Alle/Einzeln herunterladen
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
    public function store(Request $request, Receipt $receipt)
    {
        return $receipt->pdf()->download($receipt->name . '.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipts\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        return $receipt->pdf()->stream();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipts\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $receipt)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipts\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receipts\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Receipt $receipt)
    {
        //
    }
}
