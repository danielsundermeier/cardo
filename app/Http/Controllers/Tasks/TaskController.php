<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use App\Models\Tasks\Category;
use App\Models\Tasks\Task;
use App\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $baseViewPath = 'task';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Task::with([
                    'category',
                    'partner',
                ])
                ->category($request->input('category_id'))
                ->isCompleted($request->input('is_completed'))
                ->priority($request->input('priority'))
                ->search($request->input('searchtext'))
                ->partner($request->input('staff_id'))
                ->orderBy('is_completed', 'ASC')
                ->orderBy('priority', 'ASC')
                ->orderBy('name', 'ASC')
                ->paginate();
        }

        return view($this->baseViewPath . '.index')
            ->with('categories', Category::orderBy('name', 'ASC')->get())
            ->with('priorities', Task::PRIORITIES)
            ->with('partners', Partner::staff()
                ->orderByName()
                ->get());
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
        $user = auth()->user();

        return Task::create($request->validate([
            'name' => 'required|string',
            'category_id' => 'required|integer|exists:task_category,id'
        ]) + [
            'staff_id' => $user->partner->id,
            'creator_id' => $user->id,
            'priority' => 1,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tasks\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $task->load([
                    'category',
                    'completer',
                    'creator',
                    'partner',
                ]))
            ->with('priorities', Task::PRIORITIES)
            ->with('partners', Partner::staff()->orderByName()->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tasks\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $task)
            ->with('categories', Category::orderBy('name', 'ASC')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $attributes = $request->validate([
            'name' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|integer|exists:task_category,id',
            'description' => 'sometimes|nullable|string',
            'staff_id' => 'sometimes|nullable|integer|exists:partners,id',
            'priority' => 'sometimes|required|integer',
            'note' => 'sometimes|nullable|string',
        ]);

        $task->update($attributes);

        if ($request->wantsJson()) {
            return $task;
        }

        return redirect($task->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task)
    {
        if ($isDeletable = $task->isDeletable()) {
            $task->delete();
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
