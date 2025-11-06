<?php

namespace App\Http\Controllers;

use App\Models\Post_categories;
use Illuminate\Http\Request;

class PostCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Post_categories::all();
        return response()->json([
            'data' => $data,
            'status' => 'True',
            'message' => 'here are all categories',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categories_id' => ['required', 'string', 'max:255'],
            'blog_id' => ['required', 'string', 'max:255'],
        ]);

        $data = Post_categories::query()->create([
            'categories_id' => $request['categories_id'],
            'blog_id' => $request['blog_id'],
        ]);

        if (is_null($data)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not creat new categories'
            ]);
        }

        return response()->json([
            'data' => $data,
            'status' => 'True',
            'message' => 'creat new categories completed'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $data = Post_categories::query()->find($id);
        if (is_null($data)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not find categories with id equal to ' . $id
            ]);
        }

        return response()->json([
            'data' => $data,
            'status' => 'True',
            'message' => 'here it is categories with id equal to ' . $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'categories_id' => ['string', 'max:255'],
            'blog_id' => ['string', 'max:255'],
        ]);

        $data = Post_categories::query()->find($id);
        if (is_null($data)) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such categories to update'
            ]);
        }

        Post_categories::query()->find($id)->update([
            'categories_id' => $request['categories_id'] ?? $data['categories_id'],
            'blog_id' => $request['blog_id'] ?? $data['blog_id'],
        ]);

        $data = $data->refresh();
        return response()->json([
            'data' => $data,
            'status' => 'True',
            'message' => 'update completed'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Post_categories::query()->find($id);
        if (is_null($data)) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such categories to delete'
            ]);
        }

        $data->delete();
        return response()->json([
            'status' => 'True',
            'message' => 'deleted complete'
        ]);
    }
}
