<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::all();
        return response()->json([
            'data' => $blogs,
            'status' => 'True',
            'message' => 'here are all your blogs',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required'],
            'categories' => ['array', 'exists:categories,id']
        ]);
        $blog = Blog::query()->create([
            'title' => $request['title'],
            'user_id' => auth()->id(),
            'body' => $request['body'],
        ]);

        if (is_null($blog)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not creat new post'
            ]);
        }

        if (!empty($request['categories']))
            $blog->categories()->attach($request['categories']);

        return response()->json([
            'data' => $blog,
            'categories' => $request['categories'],
            'status' => 'True',
            'message' => 'creat new post completed'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::query()->find($id);
        if (is_null($blog)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not find post with id equal to ' . $id
            ]);
        }
        $categories = Blog::with(['categories:name'])->find($id);
        return response()->json([
            // 'data' => $blog,
            'categories' => $categories,
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
            'title' => ['string', 'max:255'],
            'body' => [],
            'categories' => []
        ]);

        $blog = Blog::query()->find($id);
        if (is_null($blog)) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such post to update'
            ]);
        }

        if ($blog['user_id'] != Auth()->id())
            return response()->json([
                'status' => 'False',
                'message' => 'no such post to update'
            ]);

        Blog::query()->find($id)->update([
            'title' => $request['title'] ?? $blog['title'],
            'body' => $request['body'] ?? $blog['body'],
        ]);

        if (!empty($request['categories']))
            $blog->categories()->sync($request['categories']);

        $blog = $blog->refresh();
        return response()->json([
            'data' => $blog,
            'status' => 'True',
            'message' => 'update completed'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::query()->find($id);
        if (is_null($blog) or $blog['user_id'] != auth()->id()) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such post to delete'
            ]);
        }

        $blog->delete();
        return response()->json([
            'status' => 'True',
            'message' => 'deleted complete'
        ]);
    }
}
