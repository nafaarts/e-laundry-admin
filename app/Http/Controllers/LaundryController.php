<?php

namespace App\Http\Controllers;

use App\Models\Laundry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LaundryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->user()->can('admin')) {
            $laundries = Laundry::all();
            return view('laundry.index', compact('laundries'));
        }
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('role', 'laundry')->get();
        return view('laundry.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'owner' => 'required|exists:users,id',
            'no_izin' => 'required',
            'address' => 'required',
            'district' => 'required',
            'city' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($request->image) {
            $image_name = time() . rand(100, 999) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/img/laundry'), $image_name);
        } else {
            $image_name = 'default.jpg';
        }

        Laundry::create([
            'user_id' => $request->owner,
            'name' => $request->name,
            'no_izin' => $request->no_izin,
            'address' => $request->address,
            'district' => $request->district,
            'city' => $request->city,
            'lat' => $request->lat,
            'long' => $request->long,
            'image' => $image_name,
            'has_pickup' => $request->has_pickup ?? 0
        ]);

        return redirect()->route('laundry.index')->with('success', 'Laundry has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Laundry  $laundry
     * @return \Illuminate\Http\Response
     */
    public function show(Laundry $laundry)
    {
        if (request()->user()->can('admin')) {
            return view('laundry.detail', compact('laundry'));
        }
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Laundry  $laundry
     * @return \Illuminate\Http\Response
     */
    public function edit(Laundry $laundry)
    {
        $users = User::all();
        return view('laundry.edit', compact('users', 'laundry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laundry  $laundry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Laundry $laundry)
    {
        $request->validate([
            'name' => 'required',
            'owner' => 'required|exists:users,id',
            'no_izin' => 'required',
            'address' => 'required',
            'district' => 'required',
            'city' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($request->has('image')) {
            if ($laundry->image != 'default.jpg') File::delete(public_path('/img/laundry/') . $laundry->image);
            $image_name = time() . rand(100, 999) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/img/laundry'), $image_name);
        }

        $laundry->update([
            'user_id' => $request->owner,
            'name' => $request->name,
            'no_izin' => $request->no_izin,
            'address' => $request->address,
            'district' => $request->district,
            'city' => $request->city,
            'lat' => $request->lat,
            'long' => $request->long,
            'image' => $image_name ?? $laundry->image,
            'has_pickup' => $request->has_pickup ?? 0
        ]);

        if (request()->user()->can('admin')) {
            return redirect()->route('laundry.index')->with('success', 'Laundry successfully updated');
        }

        return redirect('/detail')->with('success', 'Laundry successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laundry  $laundry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Laundry $laundry)
    {
        File::delete(public_path('/img/laundry/') . $laundry->image);
        $laundry->delete();
        return redirect()->route('laundry.index')->with('success', 'laundry has been deleted');
    }
}
