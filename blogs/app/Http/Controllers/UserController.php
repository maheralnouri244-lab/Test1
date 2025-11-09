<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $validated['password'] = bcrypt($validated['password']);
        return User::create($validated);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => '',
            'email' => 'email|unique:users',
        ]);
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'status' => 'False',
                'message' => 'no such user'
            ]);
        }
        User::query()->find($id)->update([
            'name' => $request['name'] ?? $user['name'],
            'email' => $request['email'] ?? $user['email'],
        ]);
        $user = $user->refresh();
        return $user;
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'User deleted']);
    }

}
