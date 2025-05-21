<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderCapability;
use App\Models\OrderSiteContact;
use App\Models\OrderSpecialty;
use App\Models\OrderTimeSlot;
use App\Models\OrderDetails;
use App\Models\Ticket;
use App\Models\Trailer;
use App\Models\Truck;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            return view('backend.home', ['orders' => [], 'message' => 'There is no Job Assign To you']);
        } else {
            // ✅ Current logged-in user ke assigned orders fetch karein
            $orders = Ticket::where('user_id', $user->id)->where('status', 'pending')->get();

            if ($orders->isEmpty()) {
                return view('backend.home', ['orders' => [], 'message' => 'There is no Job Assign To you']);
            }
            $trucks = Truck::with('trailers')->where('user_id', $user->id)->get();
            $pendingOrders = Ticket::where('user_id', $user->id)->where('status', 'pending')->count();
            return view('backend.home', compact('orders', 'pendingOrders', 'trucks'))->with('message', null);
        }
    }

    public function Notify()
    {
        return view('backend.notification.index');
    }

    // public function pendingorder()
    // {
    //     $threshold = Carbon::now()->subMinutes(5); // 5 minutes pehle ka time
    //     $deleted = OrderDetails::where('status', 'pending')
    //         ->where('created_at', '<', $threshold)
    //         ->delete();

    //     $this->info("$deleted pending truck records deleted.");
    // }
}
