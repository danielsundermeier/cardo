<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comments\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type = '', $model = null)
    {
        if ($model)
        {
            return $model->comments()
                ->with([
                    'user',
                ])
                ->latest()
                ->paginate(5);
        }

        return Comment::with([
            'user',
            'commentable',
        ])
            ->latest()
            ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $type, $model)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'text' => 'required|string',
        ]);

        return $model->comments()->create($attributes)->load([
            'user',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comments\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Comment $comment)
    {
        if ($isDeletable = $comment->isDeletable()) {
            $comment->delete();
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
    }
}
