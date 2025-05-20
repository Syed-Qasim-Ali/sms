<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EventPickDrop;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\UsersArrival;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes\Storage;



class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $event = UsersArrival::where('ticket_uuid', $request->ticket_uuid)->first();

        // return view('backend.events.index', compact('event'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'material'     => 'required|string|max:255',
            'quantity'     => 'required|numeric',
            'ticket_uuid'  => 'required|uuid',
            'pickup_lat'   => 'required|numeric',
            'pickup_lng'   => 'required|numeric',
        ]);

        // Check if the ticket_uuid already exists
        $existing = EventPickDrop::where('ticket_uuid', $request->ticket_uuid)->first();

        if ($existing) {
            return redirect()->route('pickup', ['ticket_uuid' => $request->ticket_uuid])->with('error', 'Pickup already added for this Ticket ID.');
        }

        // Create new record
        EventPickDrop::create([
            'material'     => $request->material,
            'quantity'     => $request->quantity,
            'ticket_uuid'  => $request->ticket_uuid,
            'pickup_lat'   => $request->pickup_lat,
            'pickup_lng'   => $request->pickup_lng,
            'pickup_time'  => Carbon::now(),
            'status'       => 'pending',
        ]);

        return redirect()->route('pickup', ['ticket_uuid' => $request->ticket_uuid])->with('success', 'Pickup Location & Time added successfully!');
    }

    /**
     * Display the specified resource.
     */

    public function show($ticket_uuid)
    {
        $event = UsersArrival::where('ticket_uuid', $ticket_uuid)->firstOrFail();

        return view('backend.events.index', compact('event'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function pickup($ticket_uuid)
    {

        $status = Ticket::where('uuid',  $ticket_uuid)->first();

        // Check agar koi open ticket nahi mila
        if (!$status) {
            return back()->with('error', 'No open tickets found.');
        }

        $order = Order::with('ordersitecontact', 'ordertimeslot', 'company')
            ->where('order_number', $status->order_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found for this ticket.');
        }

        $checkedin = UsersArrival::where('ticket_uuid', $ticket_uuid)->first();

        $pickuplatlng = EventPickDrop::where('ticket_uuid', $ticket_uuid)->first();

        return view('backend.tickets.pickup', compact('order', 'status', 'checkedin', 'pickuplatlng'));
    }

    public function upload(Request $request, $ticket_uuid)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pickup = EventPickDrop::where('ticket_uuid', $ticket_uuid)->firstOrFail();

        if ($request->hasFile('image')) {
            // Check if an old image exists and delete it if present in storage
            if (!empty($pickup->image) && Storage::disk('public')->exists($pickup->image)) {
                Storage::disk('public')->delete($pickup->image);
            }

            // Store the new image in the 'trucks/images' directory
            $validatedData['image'] = $request->file('image')->store('pickup/images', 'public');

            $pickup->image = $validatedData['image'];
            $pickup->save();
        }

        return back()->with('success', 'Image uploaded successfully.');
    }

    public function dropoff(Request $request, $ticket_uuid)
    {
        // Find the existing row by ticket UUID
        $dropoff = EventPickDrop::where('ticket_uuid', $ticket_uuid)->first();

        if (!$dropoff) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }

        // Update dropoff data
        $dropoff->drop_lat = $request->drop_lat;
        $dropoff->drop_lng = $request->drop_lng;
        $dropoff->drop_time = now(); // or $request->drop_time if you're sending it manually

        $dropoff->save();

        return back()->with('success', 'Dropoff details updated successfully.');
    }
}
