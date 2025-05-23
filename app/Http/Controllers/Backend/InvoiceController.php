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
        $invoice = Invoice::findOrFail($invoice_id);
        // dd($invoice->ticket);
        $orders = Order::with('tickets')->where('order_number', $invoice->order_number)->first();

        $job = Job::findOrFail($orders->job);
        return view('backend.invoices.index', compact('invoice', 'orders', 'job'));
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
        // dd($tickets->first()->order_number);
        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . uniqid(),
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'order_number' => $tickets->first()->order_number,
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

    public function ShowDetails($invoice_id)
    {
                $invoice = Invoice::findOrFail($invoice_id);
              $orders = Order::with('tickets')->where('order_number', $invoice->order_number)->first();
          return view('backend.invoices.detail', compact('invoice', 'orders'));
    }
}
