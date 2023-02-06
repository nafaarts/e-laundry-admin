<?php

namespace App\Http\Controllers;

use App\Models\Laundry;
use App\Models\LaundryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LaundryServiceController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $laundry = Laundry::findOrFail(request('laundry'));
        return view('service.create', compact('laundry'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $laundry = Laundry::findOrFail($request->laundry);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'unit' => 'required',
            'icon' => 'required|image|dimensions:width=100,height=100',
        ]);

        $icon = time() . $request->icon->getClientOriginalName();
        $request->icon->move(public_path('img/icon'), $icon);

        $laundry->services()->create([
            'name' => $request->name,
            'description' => $request->description,
            'unit' => $request->unit,
            'price' => $request->price,
            'icon' => $icon,
        ]);

        return redirect()->route('laundry.show', $laundry)->with('success', 'Service successfully added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LaundryService  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(LaundryService $service)
    {
        $laundry = Laundry::findOrFail(request('laundry'));
        return view('service.edit', compact('service', 'laundry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LaundryService  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LaundryService $service)
    {
        $laundry = Laundry::findOrFail($request->laundry);

        if ($laundry->id != $service->laundry_id) abort(403);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'unit' => 'required',
            'price' => 'required',
            'icon' => 'nullable|image|dimensions:width=100,height=100',
        ]);

        if ($request->has('icon')) {
            File::delete(public_path('img/icon/') . $service->icon);
            $icon = time() . $request->icon->getClientOriginalName();
            $request->icon->move(public_path('img/icon'), $icon);
        }

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'unit' => $request->unit,
            'price' => $request->price,
            'icon' => $icon ?? $service->icon,
        ]);

        return redirect()->route('laundry.show', $laundry)->with('success', 'Service successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LaundryService  $laundryService
     * @return \Illuminate\Http\Response
     */
    public function destroy(LaundryService $service)
    {
        $laundry = Laundry::findOrFail(request('laundry'));
        File::delete(public_path('img/icon/') . $service->icon);
        $service->delete();
        return redirect()->route('laundry.show', $laundry)->with('success', 'Service successfully deleted!');
    }
}
