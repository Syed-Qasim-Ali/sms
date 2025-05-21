<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show($invoice_id)
    {
        // Retrieve the invoice by ID
        $invoice = Invoice::findOrFail($invoice_id);

        // Retrieve the associated order based on the invoice's order_number
        $orders = Order::with('tickets')->where('order_number', $invoice->order_number)->first();

        // Retrieve the job details related to the order
        $job = Job::findOrFail($orders->job_id);

        // Pass the data to the view
        return view('backend.invoices.show', compact('invoice', 'orders', 'job'));
    }

    public function generateInvoice(Request $request)
    {
        $request->validate([
            'tickets' => 'required|array',
            'tickets.*' => 'exists:tickets,uuid'
        ]);
        $selectedTicketsUUID = $request->input('tickets');
        // dd($selectedTicketsUUID);
        // Find tickets by UUIDs
        $tickets = Ticket::whereIn('uuid', $selectedTicketsUUID)->get();

        // Calculate total amount based on tickets
        $totalAmount = $tickets->sum(fn($ticket) => $ticket->order->quantity * $ticket->order->pay_rate);

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . uniqid(),
            'total_amount' => $totalAmount,
            'payment_status' => 'pending'
        ]);

        // Update each ticket with the new invoice ID
        foreach ($tickets as $ticket) {
            $ticket->invoice_id = $invoice->id;
            $ticket->save();
        }

        // Return response with the invoice URL to redirect to
        return response()->json([
            'success' => true,
            'invoice_url' => route('invoices.show', $invoice->id)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
