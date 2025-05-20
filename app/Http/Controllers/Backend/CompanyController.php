<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':company-list|company-create|company-edit|company-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':company-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':company-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':company-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) {
            $companies = Company::orderBy('id', 'DESC')->get();
        } else {
            $companies = Company::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        return view('backend.companies.index', compact('companies'))->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required',
            'email' => 'required|email|unique:companies,email',
            'company_capabilities' => 'nullable|string|max:255',
            'company_specialties' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'status' => 'required|in:active,inactive'
        ]);
        $validatedData['user_id'] = auth()->id();
        Company::create($validatedData);
        return redirect()->route('companies.index')->with('success', 'Company added successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::where('id', $id)->first();
        return view('backend.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::find($id);
        return view('backend.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'company_name' => 'required',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'company_capabilities' => 'nullable|string|max:255',
            'company_specialties' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'status' => 'required|in:active,inactive'
        ]);

        $company->fill($validatedData);

        if (!$company->isDirty()) {
            return redirect()->route('companies.index')->with('info', 'No changes were made.');
        }

        $company->save();
        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::find($id)->delete();
        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully');
    }
}
