<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Capability;
use App\Models\Company;
use App\Models\Job;
use App\Models\Order;
use App\Models\OrderCapability;
use App\Models\OrderDetails;
use App\Models\OrderSiteContact;
use App\Models\OrderSpecialty;
use App\Models\OrderTimeSlot;
use App\Models\Specialty;
use App\Models\Ticket;
use App\Models\Truck;
use App\Models\TruckDetals;
use App\Models\User;
use Illuminate\Http\Middleware\TrustHosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':orders-list|orders-create|orders-edit|orders-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':orders-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':orders-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':orders-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('super-admin')) {
            $orders = Order::with('tickets')->orderBy('id', 'DESC')->get();
        } else {
            $orders = Order::with('tickets')->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        return view('backend.orders.index', compact('orders'))->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $draftOrder = Order::where('user_id', Auth::user())->where('status', 'draft')->latest()->first();

        return view('backend.orders.create', compact('companies', 'draftOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */

    private function generateOrderNumber()
    {
        $latestOrder = Order::latest('id')->first();
        $orderNumber = 'ORD-' . str_pad(optional($latestOrder)->id + 1, 6, '0', STR_PAD_LEFT);
        return $orderNumber;
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'nullable|integer',
            'job' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'pay_rate' => 'nullable|numeric',
            'pay_rate_type' => 'nullable|string|max:50',
            'start_location' => 'nullable|string|max:255',
            'start_location_lat' => 'nullable|string|max:50',
            'start_location_lng' => 'nullable|string|max:50',
            'end_location' => 'nullable|string|max:255',
            'end_location_lat' => 'nullable|string|max:50',
            'end_location_lng' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer',
            'instruction' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        $orderNumber = $this->generateOrderNumber();

        $order = Order::create([
            'order_number' => $orderNumber,
            'company_id' => $request->company_id,
            'user_id' => Auth::user()->id,
            'job' => $request->job,
            'date' => $request->date,
            'pay_rate' => $request->pay_rate,
            'pay_rate_type' => $request->pay_rate_type,
            'start_location' => $request->start_location,
            'start_location_lat' => $request->start_lat,
            'start_location_lng' => $request->start_lon,
            'end_location' => $request->end_location,
            'end_location_lat' => $request->end_lat,
            'end_location_lng' => $request->end_lon,
            'material' => $request->material,
            'quantity' => $request->quantity,
            'instruction' => $request->instruction,
            'status' => $request->status,
        ]);

        if (!empty($request->capabilities)) {
            foreach ($request->capabilities as $capability) {
                OrderCapability::create([
                    'order_number' => $order->order_number,
                    'capability' => $capability,
                ]);
            }
        }

        if (!empty($request->specialties)) {
            foreach ($request->specialties as $specialty) {
                OrderSpecialty::create([
                    'order_number' => $order->order_number,
                    'specialty' => $specialty,
                ]);
            }
        }

        if (!empty($request->site_contacts)) {
            foreach ($request->site_contacts as $contact) {
                OrderSiteContact::create([
                    'order_number' => $order->order_number,
                    'site_contact' => $contact,
                ]);
            }
        }

        if (!empty($request->timeslots['start_time'])) {
            foreach ($request->timeslots['start_time'] as $index => $start_time) {
                OrderTimeSlot::create([
                    'order_number' => $order->order_number,
                    'truck_quantity' => $request->timeslots['quantity'][$index] ?? 1,
                    'start_time' => $start_time,
                ]);
            }
        }
        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('ordercapacity', 'orderspecialty', 'ordersitecontact', 'ordertimeslot', 'tickets')->findOrFail($id);
        $trucks = Truck::where('company_id', $order->company_id)->get();
        $lastTimeSlots = OrderTimeSlot::where('order_number', $order->order_number)
            ->orderBy('start_time', 'desc')
            ->with('tickets')
            ->get();

        // timeSlotCounts ko prepare karein
        $timeSlotCounts = $lastTimeSlots->mapWithKeys(function ($slot) {
            return [
                $slot->id => [
                    'accepted' => $slot->tickets->where('status', 'accepted')->count(),
                    'pending' => $slot->tickets->where('status', 'pending')->count(),
                ]
            ];
        });

        return view('backend.orders.show', compact('order', 'trucks', 'lastTimeSlots', 'timeSlotCounts'))->with([
            'pusherAppKey' => config('broadcasting.connections.pusher.key'),
            'pusherAppCluster' => config('broadcasting.connections.pusher.options.cluster'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orders = Order::with('ordercapacity', 'orderspecialty', 'ordersitecontact', 'ordertimeslot')->findOrFail($id);
        $companies = Company::all();
        $jobs = Job::all();
        $capabilities = Capability::all();
        $specialties = Specialty::all();
        $selectedCapabilities = $orders->ordercapacity ? $orders->ordercapacity->pluck('capability')->toArray() : [];
        $selectedSpecialties = $orders->orderspecialty ? $orders->orderspecialty->pluck('specialty')->toArray() : [];
        return view('backend.orders.edit', compact(
            'orders',
            'companies',
            'jobs',
            'capabilities',
            'specialties',
            'selectedCapabilities',
            'selectedSpecialties'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'company_id' => 'nullable|integer',
            'job' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'pay_rate' => 'nullable|numeric',
            'pay_rate_type' => 'nullable|string|max:50',
            'start_location' => 'nullable|string|max:255',
            'start_location_lat' => 'nullable|string|max:50',
            'start_location_lng' => 'nullable|string|max:50',
            'end_location' => 'nullable|string|max:255',
            'end_location_lat' => 'nullable|string|max:50',
            'end_location_lng' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer',
            'instruction' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        // Find the order by ID or fail if it doesn't exist
        $order = Order::findOrFail($id);

        // Update the order with the new data
        $order->update([
            'company_id' => $request->company_id,
            'user_id' => Auth::user()->id,
            'job' => $request->job,
            'date' => $request->date,
            'pay_rate' => $request->pay_rate,
            'pay_rate_type' => $request->pay_rate_type,
            'start_location' => $request->start_location,
            'start_location_lat' => $request->start_lat,
            'start_location_lng' => $request->start_lon,
            'end_location' => $request->end_location,
            'end_location_lat' => $request->end_lat,
            'end_location_lng' => $request->end_lon,
            'material' => $request->material,
            'quantity' => $request->quantity,
            'instruction' => $request->instruction,
            'status' => $request->status,
        ]);

        // Update capabilities
        if (!is_null($request->capabilities)) {
            $existingCapabilities = OrderCapability::where('order_number', $order->order_number)->pluck('capability', 'id')->toArray();

            // Delete removed capabilities
            OrderCapability::where('order_number', $order->order_number)
                ->whereNotIn('capability', array_values($request->capabilities))
                ->delete();

            // Update or insert new capabilities
            foreach ($request->capabilities as $capabilityId => $capability) {
                if (in_array($capability, $existingCapabilities)) {
                    continue; // Skip if already exists
                }

                OrderCapability::updateOrCreate(
                    ['order_number' => $order->order_number, 'capability' => $capability],
                    ['capability' => $capability]
                );
            }
        }

        // Update specialties
        if (!is_null($request->specialties)) {
            $existingSpecialties = OrderSpecialty::where('order_number', $order->order_number)->pluck('specialty', 'id')->toArray();

            // Delete removed specialties
            OrderSpecialty::where('order_number', $order->order_number)
                ->whereNotIn('specialty', array_values($request->specialties))
                ->delete();

            // Update or insert new specialties
            foreach ($request->specialties as $specialtyId => $specialty) {
                if (in_array($specialty, $existingSpecialties)) {
                    continue;
                }

                OrderSpecialty::updateOrCreate(
                    ['order_number' => $order->order_number, 'specialty' => $specialty],
                    ['specialty' => $specialty]
                );
            }
        }

        // Update site contacts
        if (!is_null($request->site_contacts)) {
            $existingContacts = OrderSiteContact::where('order_number', $order->order_number)->pluck('site_contact', 'id')->toArray();

            // Delete removed contacts
            OrderSiteContact::where('order_number', $order->order_number)
                ->whereNotIn('site_contact', array_values($request->site_contacts))
                ->delete();

            // Update or insert new contacts
            foreach ($request->site_contacts as $contactId => $contact) {
                if (in_array($contact, $existingContacts)) {
                    continue;
                }

                OrderSiteContact::updateOrCreate(
                    ['order_number' => $order->order_number, 'site_contact' => $contact],
                    ['site_contact' => $contact]
                );
            }
        }

        // Store or Update Order Time Slots
        if (!is_null($request->timeslots) && !is_null($request->timeslots['start_time'])) {
            $existingTimeslots = OrderTimeSlot::where('order_number', $order->order_number)
                ->pluck('start_time', 'id')
                ->toArray();

            // Delete removed timeslots
            OrderTimeSlot::where('order_number', $order->order_number)
                ->whereNotIn('start_time', $request->timeslots['start_time'])
                ->delete();

            // Insert or update timeslots
            foreach ($request->timeslots['start_time'] as $index => $start_time) {
                $truck_quantity = $request->timeslots['quantity'][$index] ?? 1;

                OrderTimeSlot::updateOrCreate(
                    [
                        'order_number' => $order->order_number,
                        'start_time' => $start_time
                    ],
                    [
                        'truck_quantity' => $truck_quantity
                    ]
                );
            }
        }
        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        $order->delete();
        OrderCapability::where('order_number', $order->order_number)->delete();
        OrderSpecialty::where('order_number', $order->order_number)->delete();
        OrderTimeSlot::where('order_number', $order->order_number)->delete();
        OrderSiteContact::where('order_number', $order->order_number)->delete();
        Ticket::where('order_number', $order->order_number)->delete();
        return redirect()->route('orders.index')->with('success', 'Order and related data deleted successfully!');
    }

    public function getCompanyData($id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $user = $company->user_id;

        if (!$user) {
            return response()->json(['error' => 'User not found for this company'], 404);
        }

        // Fetching data based on user_id
        $jobs = Job::where('user_id', $user)->get();
        $capabilities = Capability::where('user_id', $user)->get();
        $specialties = Specialty::where('user_id', $user)->get();

        return response()->json([
            'jobs' => $jobs,
            'capabilities' => $capabilities,
            'specialties' => $specialties
        ]);
    }

    public function acceptOrder($id)
    {
        //        $order = Order::findOrFail($id);
        $ticket = Ticket::where('uuid', $id)->first();
        if (!$ticket) {
            return redirect()->route('home')->with('error', 'Ticket not found for the given order number.');
        }

        // Update ticket status using uuid
        $ticket->update(['status' => 'open']);

        // Update order with user_id from ticket
        // $order->user_id = $ticket->user_id;
        // $order->save();
        return redirect()->route('home')->with('success', 'Order accepted successfully.');
    }

    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);
        $ticket = Ticket::where('order_number', $order->order_number)->first();
        if (!$ticket) {
            return redirect()->route('home')->with('error', 'Ticket not found for the given order number.');
        }
        $ticket->status = 'rejected';
        $ticket->save();
        return redirect()->route('home')->with('success', 'Order rejected successfully.');
    }


    public function toggleStatus(Order $order)
    {
        $order->status = $order->status === 'draft' ? 'active' : 'draft';
        $order->save();

        return redirect()->back()->with('success', 'Order status updated.');
    }
}
