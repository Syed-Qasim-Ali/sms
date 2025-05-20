<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SpecialtyController extends Controller
{

    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':specialties-list|specialties-create|specialties-edit|specialties-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':specialties-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':specialties-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':specialties-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('super-admin')) {
            $specialties = Specialty::orderBy('id', 'DESC')->get();
        } else {
            $specialties = Specialty::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        return view('backend.specialties.index', compact('specialties'))->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.specialties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ValidateData = $request->validate([
            'name' => 'required',
            'status' => 'required|in:active,inactive'
        ]);
        $ValidateData['user_id'] = auth()->id();
        Specialty::create($ValidateData);
        return redirect()->route('specialties.index')->with('success', 'Specialty added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specialty = Specialty::find($id);
        return view('backend.specialties.show', compact('specialty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specialty = Specialty::find($id);
        return view('backend.specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialty $specialty)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $specialty->fill($validatedData);

        if (!$specialty->isDirty()) {
            return redirect()->route('specialties.index')->with('info', 'No changes were made.');
        }

        $specialty->save();
        return redirect()->route('specialties.index')->with('success', 'Specialty updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specialty = Specialty::find($id)->delete();
        return redirect()->route('specialties.index')
            ->with('success', 'Specialty deleted successfully');
    }
}
