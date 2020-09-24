<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Models\Receipts\Receipt;
use Illuminate\Http\Request;

class PayController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipts\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        $receipt->pay();

        if ($request->wantsJson()) {
            return $receipt;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Beleg bezahlt.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receipts\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Receipt $receipt)
    {
        $receipt->pay(false);

        $status = [
            'type' => 'success',
            'text' => 'Beleg nicht bezahlt.',
        ];

        return redirect($receipt->path)
            ->with('status', $status);
    }
}
