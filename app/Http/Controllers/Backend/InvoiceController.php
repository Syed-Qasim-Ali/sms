<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($order_number)
    {
   
        $orders = Order::with('tickets')->where('order_number', $order_number)->first();
        $job = Job::where('id', $orders->job)->first();
        $totalAmount = $orders->quantity * $orders->pay_rate;
        return view('backend.invoices.index', compact('orders', 'job', 'totalAmount'));
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
        //
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
}
