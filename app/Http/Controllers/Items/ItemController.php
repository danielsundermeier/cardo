<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Items\Item;
use App\Models\Items\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $baseViewPath = 'item';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Item::with([
                    'course',
                    'unit',
                ])
                ->orderBy('name', 'ASC')
                ->paginate();
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
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        $attributes['unit_id'] = Unit::first()->id;
        $attributes['tax'] = 0.19;

        return Item::create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Items\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Items\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $courses = Course::select('courses.*')
            ->where(function (Builder $query) use ($item) {
                $query->doesntHave($item->is_subscription ? 'subscription_item' : 'item');
                if ($item->course_id) {
                    $query->orWhere('id', $item->course_id);
                }
            })
            ->orderBy('name', 'ASC')
            ->get();

        return view($this->baseViewPath . '.edit')
            ->with('model', $item)
            ->with('units', Unit::orderBy('name', 'ASC')->get())
            ->with('courses', $courses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Items\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'unit_id' => 'required|int|exists:units,id',
            'unit_price_formatted' => 'required|formatted_number',
            'tax' => 'required|numeric',
            'revenue_account_number' => 'nullable|int',
            'expense_account_number' => 'nullable|int',
            'course_id' => 'nullable|int|exists:courses,id',
        ]);

        $attributes['is_flatrate'] = $request->has('is_flatrate');
        $attributes['is_subscription'] = $request->has('is_subscription');

        $item->update($attributes);

        if ($request->wantsJson()) {
            return $item;
        }

        return redirect($item->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Items\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item)
    {
        if ($isDeletable = $item->isDeletable()) {
            $item->delete();
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
