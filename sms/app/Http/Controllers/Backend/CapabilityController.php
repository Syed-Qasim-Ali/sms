<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Capability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CapabilityController extends Controller
{

    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':capabilities-list|capabilities-create|capabilities-edit|capabilities-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':capabilities-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':capabilities-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':capabilities-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('super-admin')) {
            $capabilities = Capability::orderBy('id', 'DESC')->get();
        } else {
            $capabilities = Capability::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        return view('backend.capabilities.index', compact('capabilities'))->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.capabilities.create');
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
        Capability::create($ValidateData);
        return redirect()->route('capabilities.index')->with('success', 'Capability added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $capability = Capability::find($id);
        return view('backend.capabilities.show', compact('capability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $capabilty = Capability::find($id);
        return view('backend.capabilities.edit', compact('capabilty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Capability $capability)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $capability->fill($validatedData);

        if (!$capability->isDirty()) {
            return redirect()->route('capabilities.index')->with('info', 'No changes were made.');
        }

        $capability->save();
        return redirect()->route('capabilities.index')->with('success', 'Capabilities updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $capability = Capability::find($id)->delete();
        return redirect()->route('capabilities.index')
            ->with('success', 'Capability deleted successfully');
    }
}
