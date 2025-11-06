<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();
        return response()->json([
            'data' => $comments,
            'status' => 'True',
            'message' => 'here are all comments',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => ['required', 'string', 'max:255'],
            'body' => ['required'],
        ]);

        $comment = Comment::query()->create([
            'post_id' => $request['post_id'],
            'user_id' => auth()->id(),
            'body' => $request['body'],
        ]);

        if (is_null($comment)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not creat new comment'
            ]);
        }

        return response()->json([
            'data' => $comment,
            'status' => 'True',
            'message' => 'creat new comment completed'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $comment = Comment::query()->find($id);
        if (is_null($comment)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not find post with id equal to ' . $id
            ]);
        }

        return response()->json([
            'data' => $comment,
            'status' => 'True',
            'message' => 'here it is post with id equal to ' . $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'post_id' => ['string', 'max:255'],
            'body' => [],
        ]);

        $comment = Comment::query()->find($id);
        if (is_null($comment) or $comment['user_id'] != auth()->id()) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such post to update'
            ]);
        }

        Comment::query()->find($id)->update([
            'post_id' => $request['post_id'] ?? $comment['post_id'],
            'body' => $request['body'] ?? $comment['body'],
        ]);

        $comment = $comment->refresh();
        return response()->json([
            'data' => $comment,
            'status' => 'True',
            'message' => 'update completed'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::query()->find($id);
        if (is_null($comment) or $comment['user_id'] != auth()->id()) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such post to delete'
            ]);
        }

        $comment->delete();
        return response()->json([
            'status' => 'True',
            'message' => 'deleted complete'
        ]);
    }
}
