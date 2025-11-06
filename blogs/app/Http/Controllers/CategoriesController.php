<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = categories::all();
        return response()->json([
            'data' => $categories,
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
            'name' => 'required|string|unique:categories,name|max:255',
        ]);

        $blog = categories::query()->create([
            'name' => $request['name'],
        ]);

        if (is_null($blog)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not creat new categorie'
            ]);
        }

        return response()->json([
            'data' => $blog,
            'status' => 'True',
            'message' => 'creat new categorie completed'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categorie = categories::query()->find($id);
        if (is_null($categorie)) {
            return response()->json([
                'status' => 'False',
                'message' => 'can not find categorie with id equal to ' . $id
            ]);
        }

        return response()->json([
            'data' => $categorie,
            'status' => 'True',
            'message' => 'here it is categorie with id equal to ' . $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('categories', 'name')->ignore($id),
                'max:255'
            ]
        ]);

        $categorie = categories::query()->find($id);
        if (is_null($categorie)) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such categorie to update'
            ]);
        }

        categories::query()->find($id)->update([
            'name' => $request['name'] ?? $categorie['name']
        ]);

        $categorie = $categorie->refresh();
        return response()->json([
            'data' => $categorie,
            'status' => 'True',
            'message' => 'update completed'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categorie = categories::query()->find($id);
        if (is_null($categorie)) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such categorie to delete'
            ]);
        }
        $categorie->delete();
        return response()->json([
            'status' => 'True',
            'message' => 'categorie deleted'
        ]);
    }
}
