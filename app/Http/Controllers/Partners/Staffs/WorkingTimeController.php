<?php

namespace App\Http\Controllers\Partners\Staffs;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use Illuminate\Http\Request;

class WorkingTimeController extends Controller
{
    protected $baseViewPath = 'staff.workingtime';

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partners\Partner  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Partner $staff)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        return view($this->baseViewPath . '.show')
            ->with('model', $staff);
    }
}
