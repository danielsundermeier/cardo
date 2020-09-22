<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Tasks\Task;
use Illuminate\Http\Request;

class CompleteController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $task->complete(auth()->user());

        if ($request->wantsJson()) {
            return $task;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Aufgabe erledigt.',
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
        $task->incomplete();

        if ($request->wantsJson()) {
            return $task;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Aufgabe unerledigt.',
            ]);
    }
}
