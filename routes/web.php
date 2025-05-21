<?php

use App\Http\Controllers\Backend\ArrivalController;
use App\Http\Controllers\Backend\CapabilityController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\JobController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SpecialtyController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\TrailerController;
use App\Http\Controllers\Backend\TruckController;
use App\Http\Controllers\HomeController as ControllersHomeController;
use App\Http\Middleware\CheckUserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['Spatie\Permission\Middleware\RoleMiddleware:super-admin'])->group(function () {
    Route::get('/impersonate/{id}', function ($id) {
        $user = User::findOrFail($id);
        if (Auth::user()->hasRole('super-admin')) {
            session(['impersonated_by' => Auth::user()->id]);
            $userModel = $user instanceof \Illuminate\Support\Collection ? $user->first() : $user;
            Auth::user()->impersonate($userModel);
        } else {
            return redirect('/home')->with('error', 'You do not have permission to impersonate.');
        }
        return redirect('/home');
    })->name('impersonate.start');
});

Route::get('/impersonate-stop', function () {
    Auth::user()->leaveImpersonation(); // ✅ correct method
    session()->forget('impersonated_by');
    return redirect('/users')->with('success', 'You are back as Admin.');
})->name('impersonate.stop');

Auth::routes();

Route::middleware(['auth', CheckUserStatus::class])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Capabilities Management
    Route::resource('/capabilities', CapabilityController::class)->middleware('can:capabilities-list');

    //Companies Management
    Route::resource('/companies', CompanyController::class)->middleware('can:company-list');

    //Specialties Management
    Route::resource('/specialties', SpecialtyController::class)->middleware('can:specialties-list');

    //Trucks Management
    Route::resource('/trucks', TruckController::class)->middleware('can:trucks-list');

    //Trailer Management
    Route::resource('trailers', TrailerController::class);

    //Jobs Management
    Route::resource('/jobs', JobController::class)->middleware('can:jobs-list');

    // Orders Management
    Route::resource('/orders', OrderController::class)->middleware('can:orders-list');

    //Reports Management
    Route::resource('/reports', ReportController::class); //->middleware('can:reports')

    // User Management
    Route::resource('/users', UserController::class)->middleware('can:users-list');

    // Role Management
    Route::resource('/roles', RoleController::class)->middleware('can:roles-list');

    // Permissions Management
    Route::resource('permissions', PermissionController::class)->middleware('can:permissions-list');

    Route::get('/pending-notification', [App\Http\Controllers\HomeController::class, 'Notify']);

    //Toggle User Status
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);

    //Company_id Se Related Data fetch
    Route::get('/get-company-data/{id}', [OrderController::class, 'getCompanyData']);

    //Order Accept
    Route::post('/orders/{id}/accept', [OrderController::class, 'acceptOrder'])->name('orders.accept');

    //Order Reject
    Route::post('/orders/{id}/reject', [OrderController::class, 'rejectOrder'])->name('orders.reject');

    //Order Status Toggle
    Route::post('/orders/{order}/toggle-status', [OrderController::class, 'toggleStatus'])
        ->name('orders.toggleStatus');

    //Invite Truck User
    Route::post('/trucks/invite', [TicketController::class, 'inviteUser']);

    //Ticket
    Route::resource('/tickets', TicketController::class);

    //Fetch Trailer by Selecting  Truck
    Route::get('/trucks/{id}/trailers', [TruckController::class, 'getTrailers']);

    Route::post('/tickets/{uuid}/arrival', [TicketController::class, 'storeArrival'])->name('tickets.arrival');

    Route::get('/dayprogress/{ticketid}', [TicketController::class, 'showDayProgress']);

    Route::resource('/events', EventController::class);

    Route::get('/events/{ticket_uuid}', [EventController::class, 'show'])->name('events.show');

    Route::get('pickup/{ticket_uuid}', [EventController::class, 'pickup'])->name('pickup');

    Route::post('/pickup/{ticket_uuid}/upload-image', [EventController::class, 'upload'])->name('image.upload');

    Route::put('/dropoff/{uuid}', [EventController::class, 'dropoff'])->name('dropoff');

    Route::post('ticket/submit/{ticket_uuid}', [TicketController::class, 'submitticket'])->name('ticket.submit');

    Route::get('/{uuid}', [TicketController::class, 'showByUuid'])->whereUuid('uuid');

    Route::post('/response/{uuid}', [TicketController::class, 'ticketresponse'])->name('response');

    Route::post('/deny/{uuid}', [TicketController::class, 'ticketresponsedeny'])->name('deny');

    //Invoices Management
    Route::get('/invoices/{invoice_id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/tickets/generate-invoice', [InvoiceController::class, 'generateInvoice'])->name('tickets.generate-invoice');
    Route::get('ticket-list/{order_number}', [TicketController::class, 'order_tickets'])->name('orders-view');


    Route::get('ticketresponse/{uuid}', [TicketController::class, 'ticketresponse']);
});
