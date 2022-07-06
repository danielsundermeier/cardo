<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Receipts\Expense;
use App\Models\Receipts\Invoice;
use App\Models\Receipts\Receipt;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Filesystem $filessytem)
    {
        $attributes = $request->validate([
            'receipt_ids' => 'required|array'
        ]);

        $receipts = Receipt::find($attributes['receipt_ids']);

        $user = auth()->user();
        $base_path = 'app/public/receipts/' . $user->id . '/';
        $path = storage_path($base_path);
        $zip_file = $path . 'belege.zip';

        if (! $filessytem->isDirectory($path)) {
            $filessytem->makeDirectory($path, 0777, true, true);
        }

        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = [];
        foreach ($receipts as $receipt) {
            $filename = Str::slug($receipt->name, '-', 'de') . '.pdf';
            $class_name = get_class($receipt);
            if ($class_name == Invoice::class) {
                $receipt->pdf()->save($path . $filename);
                $zip->addFile($path . $filename, $filename);
                $files[] = $path . $filename;
            }
            elseif ($class_name == Expense::class) {
                if (is_null($receipt->previewFile)) {
                    continue;
                }

                $zip->addFile($receipt->previewFile->storage_path, $filename);
                $files[] = $receipt->previewFile->storage_path;
            }
        }

        $zip->close();

        foreach ($files as $path) {
            unlink($path);
        }

        return [
            'path' => '/storage/receipts/' . $user->id . '/belege.zip',
        ];
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
        return $receipt->pdf($request->all())->download($receipt->name . '.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipts\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Receipt $receipt)
    {
        return $receipt->pdf($request->all())->stream();
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
