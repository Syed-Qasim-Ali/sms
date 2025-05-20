<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Trailer;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrailerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) {
            $trucks = Truck::with('trailers')->orderBy('id', 'DESC')->get();
        } else {
            $trucks = Truck::with('trailers')->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        return view('backend.trailers.index', compact('trucks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trucks = Truck::all();
        return view('backend.trailers.create', compact('trucks'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'trailer_number' => 'required|string|max:255|unique:trailers,trailer_number',
            'truck_id' => 'required|exists:trucks,id',
            'rate_modifier' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        // Create new trailer
        Trailer::create([
            'trailer_number' => $request->trailer_number,
            'truck_id' => $request->truck_id,
            'rate_modifier' => $request->rate_modifier,
            'status' => $request->status,
        ]);

        return redirect()->route('trailers.index')->with('success', 'Trailer created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $trailer = Trailer::where('id', $id)->first();
        $trucks = Truck::all();
        return view('backend.trailers.edit', compact('trailer', 'trucks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'trailer_number' => 'required|string|max:255|unique:trailers,trailer_number,' . $id,
            'truck_id' => 'required|exists:trucks,id',
            'rate_modifier' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $trailer = Trailer::findOrFail($id);
        $trailer->update([
            'trailer_number' => $request->trailer_number,
            'truck_id' => $request->truck_id,
            'rate_modifier' => $request->rate_modifier,
            'status' => $request->status,
        ]);

        return redirect()->route('trailers.index')->with('success', 'Trailer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trailer = Trailer::findOrFail($id);
        $trailer->delete();

        return redirect()->route('trailers.index')
            ->with('success', 'Trailer deleted successfully');
    }
}
