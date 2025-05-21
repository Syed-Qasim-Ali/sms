<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\TruckInvitationMail;
use App\Models\Backlog;
use App\Models\EventPickDrop;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketAssign;
use App\Models\Trailer;
use App\Models\Truck;
use App\Models\User;
use App\Models\UsersArrival;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Auth;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
if($user->hasRole('Super Admin'))
        {
$tickets = Ticket::all();
}else{            $tickets = Ticket::where('user_id', Auth::id())->get();
        }
        return view('backend.tickets.index', compact('tickets'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    // public function order_tickets(Request $request, $id)
    // {
    //     $orderno = $id;
    //     $user = Auth::user();
    //     // if ($user->hasRole('super-admin')) {
    //     // $tickets = Order::with('tickets')->get();
    //     // } else {
    //     $tickets = Ticket::where('order_number', $id)->get();
    //     // }
    //     // return $tickets;

    //     return view('backend.tickets.index', compact('tickets', 'orderno'))->with('i', ($request->input('page', 1) - 1) * 5);
    // }

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
    public function store(Request $request, $uuid)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $status = Ticket::where('status', 'open')->where('uuid', $uuid)->first();

        // Check agar koi open ticket nahi mila
        if (!$status) {
            return back()->with('error', 'No open tickets found.');
        }

        if ($status->status == 'closed') {
            return back()->with('error', 'Ticket has been closed');
        }

        $order = Order::with('ordersitecontact', 'ordertimeslot', 'company')
            ->where('order_number', $status->order_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found for this ticket.');
        }

        $assignticket = TicketAssign::where('ticket_id', $uuid)->first();

        $truck = Truck::where('id', $assignticket->truck_id)->first();

        $trailer = Trailer::where('id', $assignticket->trailer_id)->first();

        return view('backend.tickets.show', compact('order', 'status', 'truck', 'trailer'));
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


    public function storeArrival(Request $request, $uuid)
    {
        $request->validate([
            'location_lat' => 'required|numeric|between:-90,90',
            'location_lng' => 'required|numeric|between:-180,180',
        ]);

        // Find the open ticket
        $ticket = Ticket::where('uuid', $uuid)->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'No open ticket found.');
        }

        // Check if user already marked arrival
        $alreadyArrived = UsersArrival::where('ticket_uuid', $uuid)->exists();
        if ($alreadyArrived) {
            return redirect()->to('dayprogress/' . $uuid)->with('error', 'You have already marked arrival for this ticket.');
        }

        // Save arrival data
        UsersArrival::create([
            'user_id' => auth()->id(),
            'ticket_uuid' => $uuid,
            'arrival_status' => 'arrived',
            'location_lat' => $request->location_lat,
            'location_lng' => $request->location_lng,
        ]);

        // Update ticket status
        // $ticket->status = 'accepted';
        // $ticket->save();

        // Fetch site contact number from order
        $contact = Order::with('ordersitecontact')->where('order_number', $ticket->order_number)->first();

        // Example: assuming `ordersitecontact` is a relation returning a collection
        $receiverNumber = optional($contact->ordersitecontact->first())->site_contact;

        // Check if number exists
        if (!$receiverNumber) {
            return redirect()->back()->with('error', 'No site contact number found to send SMS.');
        }

        // Clean up and format the number (optional but recommended)
        $receiverNumber = preg_replace('/[^0-9]/', '', $receiverNumber); // remove dashes, spaces, etc.

        // Ensure number has +92 format
        if (!str_starts_with($receiverNumber, '92')) {
            $receiverNumber = '92' . ltrim($receiverNumber, '0');
        }
        $receiverNumber = '+' . $receiverNumber;

        // Send SMS via Twilio
        // try {
        //     $account_sid = env("TWILIO_SID");
        //     $auth_token = env("TWILIO_AUTH_TOKEN"); // fixed name to match .env
        //     $twilio_number = env("TWILIO_PHONE_NUMBER"); // fixed name to match earlier advice

        //     $client = new Client($account_sid, $auth_token);
        //     $client->messages->create($receiverNumber, [
        //         'from' => $twilio_number,
        //         'body' => "You have a truck arriving at your site. Ticket: {$ticket->uuid}"
        //     ]);
        return redirect()->to('dayprogress/' . $uuid)->with('success', 'Arrival marked and SMS sent successfully!');
        // } catch (Exception $e) {
        //     return redirect()->back()->with('error', 'Arrival marked, but SMS failed: ' . $e->getMessage());
        // }
    }

    // public function inviteUser(Request $request)
    // {
    //     $truckId = $request->input('truck_id');
    //     $orderNumber = $request->input('order_number');
    //     $timeSlotID = $request->input('time_slot_id');

    //     if (!$truckId) {
    //         return response()->json(['success' => false, 'error' => '❌ Truck ID is missing'], 400);
    //     }

    //     $truck = Truck::find($truckId);
    //     if (!$truck) {
    //         return response()->json(['success' => false, 'error' => '❌ Truck not found'], 404);
    //     }

    //     if (!$truck->user_id) {
    //         return response()->json(['success' => false, 'error' => '❌ Truck owner not found'], 404);
    //     }

    //     $user = User::find($truck->user_id);
    //     if (!$user || !$user->email) {
    //         return response()->json(['success' => false, 'error' => '❌ Truck owner email not found'], 404);
    //     }

    //     // ✅ Check if this truck is already assigned to this time slot
    //     $existingTicket = Ticket::where('truck_id', $truckId)
    //         ->where('time_slot_id', $timeSlotID)
    //         ->whereIn('status', ['pending', 'accepted']) // Optional: check only active assignments
    //         ->first();

    //     if ($existingTicket) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => '❌ This truck is already assigned to this time slot.'
    //         ], 409);
    //     }

    //     // Generate a UUID
    //     $uuid = (string) Str::uuid();

    //     $order = Ticket::create([
    //         'uuid' => $uuid,
    //         'user_id' => $user->id,
    //         'truck_id' => $truckId,
    //         'order_number' => $orderNumber,
    //         'time_slot_id' => $timeSlotID,
    //         'status' => 'pending',
    //     ]);

    //     // Send Email
    //     Mail::to($user->email)->send(new TruckInvitationMail($truck));

    //     // Optionally trigger event
    //     // event(new OrderAssigned($order, $user->id));

    //     return response()->json([
    //         'success' => true,
    //         'message' => '✅ Invitation sent successfully',
    //         'data' => $truck
    //     ]);
    // }

    public function inviteUser(Request $request)
    {
        $truckId = $request->input('truck_id');
        $orderNumber = $request->input('order_number');
        $timeSlotID = $request->input('time_slot_id');

        if (!$truckId) {
            return response()->json(['success' => false, 'error' => '❌ Truck ID is missing'], 400);
        }

        $truck = Truck::find($truckId);
        if (!$truck) {
            return response()->json(['success' => false, 'error' => '❌ Truck not found'], 404);
        }

        if (!$truck->user_id) {
            return response()->json(['success' => false, 'error' => '❌ Truck owner not found'], 404);
        }

        $user = User::find($truck->user_id);
        if (!$user || !$user->email) {
            return response()->json(['success' => false, 'error' => '❌ Truck owner email not found'], 404);
        }

        // Check if truck is already assigned to this time slot
        $existingTicket = Ticket::where('truck_id', $truckId)
            ->whereIn('status', ['pending', 'accepted']) // Active ticket
            ->first();

        if ($existingTicket) {
            // Add truck to backlog
            $this->addToBacklog($truckId, $orderNumber, $timeSlotID);

            return response()->json([
                'success' => false,
                'error' => '❌ This truck is already assigned to this time slot. Truck added to backlog.'
            ], 409);
        }

        // Generate UUID and create ticket if truck is available
        $uuid = (string) Str::uuid();

        $order = Ticket::create([
            'uuid' => $uuid,
            'user_id' => $user->id,
            'truck_id' => $truckId,
            'order_number' => $orderNumber,
            'time_slot_id' => $timeSlotID,
            'status' => 'pending',
        ]);

        // Send Email to truck owner
        if ($truck instanceof \Illuminate\Database\Eloquent\Collection) {
            $truck = $truck->first();
        }
        Mail::to($user->email)->send(new TruckInvitationMail($truck));

        return response()->json([
            'success' => true,
            'message' => '✅ Invitation sent successfully',
            'data' => $truck
        ]);
    }

    // Add to backlog function
    public function addToBacklog($truckId, $orderNumber, $timeSlotID)
    {
        $existingBacklog = Backlog::where('truck_id', $truckId)
            ->where('time_slot_id', $timeSlotID)
            ->where('status', 'pending') // Only pending items
            ->first();

        if (!$existingBacklog) {
            // If not in backlog, add it
            Backlog::create([
                'truck_id' => $truckId,
                'order_number' => $orderNumber,
                'time_slot_id' => $timeSlotID,
                'status' => 'pending',
                'priority' => 'high',  // Or 'medium', 'low' depending on your needs
            ]);
        }
    }

    public function submitticket($ticket_uuid)
    {
        $ticket = Ticket::where('uuid', $ticket_uuid)->first();
        $ticket->status = 'admin_review';
        $ticket->save();

        // Fetch site contact number from order
        $contact = Order::with('ordersitecontact')->where('order_number', $ticket->order_number)->first();

        // Example: assuming `ordersitecontact` is a relation returning a collection
        $receiverNumber = optional($contact->ordersitecontact->first())->site_contact;

        // Check if number exists
        if (!$receiverNumber) {
            return redirect()->back()->with('error', 'No site contact number found to send SMS.');
        }

        // Clean up and format the number (optional but recommended)
        $receiverNumber = preg_replace('/[^0-9]/', '', $receiverNumber); // remove dashes, spaces, etc.

        // Ensure number has +92 format
        if (!str_starts_with($receiverNumber, '92')) {
            $receiverNumber = '92' . ltrim($receiverNumber, '0');
        }
        $receiverNumber = '+' . $receiverNumber;

        // // Send SMS via Twilio
        // try {
        //     $account_sid = env("TWILIO_SID");
        //     $auth_token = env("TWILIO_AUTH_TOKEN"); // fixed name to match .env
        //     $twilio_number = env("TWILIO_PHONE_NUMBER"); // fixed name to match earlier advice

        //     $client = new Client($account_sid, $auth_token);

        //     $url = url($ticket->uuid);

        //     $client->messages->create($receiverNumber, [
        //         'from' => $twilio_number,
        //         'body' => "Ticket: {$ticket->uuid} Has Been submitted for your review staging. {{ $url }}"
        //     ]);

        return redirect()->back()->with('success', 'The status is successfully changed to under_review');
        // } catch (Exception $e) {
        //     return redirect()->back()->with('error', 'Status changed to under_review, but SMS failed: ' . $e->getMessage());
        // }
    }

    public function showDayProgress($uuid)
    {
        $status = Ticket::where('uuid', $uuid)->first();

        $order = Order::with('ordersitecontact', 'ordertimeslot', 'company')
            ->where('order_number', $status->order_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found for this ticket.');
        }

        $checkedin = UsersArrival::where('ticket_uuid', $uuid)->first();

        return view('backend.tickets.dayprogress', compact('order', 'status', 'checkedin'));
    }

    public function showByUuid($uuid)
    {
        $ticket = Ticket::with('eventpickdrop')->where('uuid', $uuid)->firstOrFail();

        $user = User::where('id', $ticket->user_id)->first();

        return view('backend.urls.index', compact('ticket', 'user'));
    }

    public function ticketresponse(Request $request, $uuid)
{
    return request->all();
    $request->validate([
        'pickup_time' => 'required|date',
        'drop_time' => 'required|date|after_or_equal:pickup_time',
        'tolls' => 'nullable|numeric',
        'images.*' => 'nullable|image|max:2048',
    ]);

    $ticket = Ticket::with('eventpickdrop')->where('uuid', $uuid)->firstOrFail();
    $event = $ticket->eventpickdrop->first();

    if ($event) {
        // Conditionally update pickup and drop times
        if ($event->pickup_time != $request->pickup_time) {
            $event->pickup_time = $request->pickup_time;
        }
        if ($event->drop_time != $request->drop_time) {
            $event->drop_time = $request->drop_time;
        }

        $event->status = 'approved';
        $event->save();

        // Update tolls and ticket status
        if ($request->filled('tolls')) {
            $ticket->tolls = $request->tolls;
        }
        $ticket->status = 'closed';
        $ticket->save();

        // Handle image uploads (store all in ticket_images table)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('pickup/images', 'public');

                // Store in ticket_images table (if you're using it)
                TicketImage::create([
                    'ticket_id' => $ticket->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Handle backlog
        $backlog = Backlog::where('truck_id', $ticket->truck_id)->first();
        if ($backlog) {
            $newUuid = (string) Str::uuid();
            Ticket::create([
                'uuid' => $newUuid,
                'user_id' => $ticket->user_id,
                'truck_id' => $backlog->truck_id,
                'order_number' => $backlog->order_number,
                'time_slot_id' => $backlog->time_slot_id,
                'status' => 'pending',
            ]);
            $backlog->delete();
        }

        return back()->with('success', 'Your response has been saved!');
    }

    return back()->with('error', 'No event found.');
}

    public function ticketresponsedeny()
    {
        return back()->with('success', 'We appreciate your feedback and will investigate the matter.');
    }
}
