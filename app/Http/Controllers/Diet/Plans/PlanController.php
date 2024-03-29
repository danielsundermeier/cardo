<?php

namespace App\Http\Controllers\Diet\Plans;

use App\Http\Controllers\Controller;
use App\Models\Diet\Meals\Meal;
use App\Models\Diet\Plans\Plan;
use App\Models\Partners\Partner;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected $baseViewPath = 'diet.plan';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Plan::orderBy('is_active', 'DESC')
                ->orderBy('name', 'ASC')
                ->paginate();
        }

        return view($this->baseViewPath . '.index')
            ->with('partners', Partner::client()->get());
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
        $attributes = $request->validate([
            'name' => 'required|string',
            'partner_id' => 'required|numeric|exists:partners,id',
        ]);

        return Plan::create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $plan)
            ->with('foods', \App\Models\Diet\Foods\Food::orderBy('name', 'ASC')->get())
            ->with('meals', Meal::with(['foods'])->orderBy('name', 'ASC')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $plan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'valid_from_formatted' => 'nullable|date_format:"d.m.Y',
        ]);

        $plan->update($attributes);

        if ($request->wantsJson()) {
            return $plan;
        }

        return redirect($plan->path)
            ->with('status', [
                'type' => 'success',
                'text' => $plan->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Plan $plan)
    {
        if ($isDeletable = $plan->isDeletable()) {
            $plan->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $plan->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $plan->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($plan->index_path)
            ->with('status', $status);
    }
}
