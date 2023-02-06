<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validateData = $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'role' => 'required',
            'password' => 'required|confirmed'
        ]);

        User::create([...$validateData, 'password' => Hash::make($request->password)]);

        return redirect()->route('users.index')->with('success', 'User has been created successfully');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validateData = $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'role' => 'required',
            'password' => 'confirmed'
        ]);

        $user->update([
            ...$validateData,
            'password' => $request->password ? Hash::make($request->password) : $user->password
        ]);

        return redirect()->route('users.index')->with('success', 'User has been updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User has been deleted successfully');
    }
}
