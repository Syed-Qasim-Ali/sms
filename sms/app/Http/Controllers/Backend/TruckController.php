<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\TruckInvitationMail;
use App\Models\Company;
use App\Models\Truck;
use App\Models\TruckDetals;
use App\Models\User;
use App\Notifications\TruckInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderAssigned;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\Auth;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':trucks-list|trucks-create|trucks-edit|trucks-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':trucks-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':trucks-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':trucks-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('super-admin')) {
            $trucks = Truck::orderBy('id', 'DESC')->get();
        } else {
            $trucks = Truck::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        return view('backend.trucks.index', compact('trucks'))->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            $companies = Company::all();
        } else {
            $companies = Company::where('user_id', auth()->id())->get();
        }
        return view('backend.trucks.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id', // Ensure company_id exists in DB
            'truck_number' => 'required|unique:trucks,truck_number',
            'truck_type' => 'required',
            'registration_number' => 'required|unique:trucks,registration_number',
            'model' => 'nullable|string|max:255',
            'truck_capabilities' => 'nullable|string|max:255',
            'truck_specialties' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1980|max:2025',
            'capacity' => 'nullable|numeric',
            'fuel_type' => 'nullable|in:diesel,petrol,electric',
            'driver_name' => 'nullable|string|max:255',
            'driver_contact' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'documents.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validatedData['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('trucks/images', 'public');
        }

        if ($request->hasFile('documents')) {
            $docPaths = [];
            foreach ($request->file('documents') as $document) {
                $docPaths[] = $document->store('trucks/documents', 'public');
            }
            $validatedData['documents'] = json_encode($docPaths);
        }

        Truck::create($validatedData);

        return redirect()->route('trucks.index')->with('success', 'Truck added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $truck = Truck::find($id);
        return view('backend.trucks.show', compact('truck'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $truck = Truck::find($id);
        return view('backend.trucks.edit', compact('truck'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Truck $truck)
    {
        $validatedData = $request->validate([
            'truck_number' => 'required|unique:trucks,truck_number,' . $truck->id,
            'truck_type' => 'required',
            'registration_number' => 'required|unique:trucks,registration_number,' . $truck->id,
            'model' => 'nullable|string|max:255',
            'truck_capabilities' => 'nullable|string|max:255',
            'truck_specialties' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1980|max:2025',
            'capacity' => 'nullable|numeric',
            'fuel_type' => 'nullable|in:diesel,petrol,electric',
            'driver_name' => 'nullable|string|max:255',
            'driver_contact' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'documents.*' => 'nullable|mimes:pdf,docx,jpg,jpeg,png|max:2048',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Check if an old image exists and delete it if present in storage
            if (!empty($truck->image) && Storage::disk('public')->exists($truck->image)) {
                Storage::disk('public')->delete($truck->image);
            }

            // Store the new image in the 'trucks/images' directory
            $validatedData['image'] = $request->file('image')->store('trucks/images', 'public');
        }



        if ($request->hasFile('documents')) {
            if (!empty($truck->documents)) {
                foreach (json_decode($truck->documents) as $oldDoc) {

                    if (!empty($oldDoc) && Storage::disk('public')->exists($oldDoc)) {
                        Storage::disk('public')->delete($oldDoc);
                    }
                }
            }
            $documentPaths = [];
            foreach ($request->file('documents') as $document) {
                $documentPaths[] = $document->store('trucks/documents', 'public');
            }
            $validatedData['documents'] = json_encode($documentPaths);
        }

        $truck->fill($validatedData);

        if (!$truck->isDirty()) {
            return redirect()->route('trucks.index')->with('info', 'No changes were made.');
        }
        $truck->update($validatedData);

        return redirect()->route('trucks.index')->with('success', 'Truck updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $truck = Truck::findOrFail($id);

        if ($truck->image && Storage::disk('public')->exists($truck->image)) {
            Storage::disk('public')->delete($truck->image);
        }

        if ($truck->documents) {
            $documents = json_decode($truck->documents, true);
            foreach ($documents as $doc) {
                if (Storage::disk('public')->exists($doc)) {
                    Storage::disk('public')->delete($doc);
                }
            }
        }

        $truck->delete();

        return redirect()->route('trucks.index')
            ->with('success', 'Truck and associated files deleted successfully');
    }
}
