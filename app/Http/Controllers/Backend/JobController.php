<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':jobs-list|jobs-create|jobs-edit|jobs-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':jobs-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':jobs-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':jobs-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) {
            $jobs = Job::orderBy('id', 'DESC')->get();
        } else {
            $jobs = Job::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        return view('backend.jobs.index', compact('jobs'))->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'created_at' => Carbon::now(),
        ]);
        $user = Auth::user();
        $validatedData['user_id'] = $user->id;

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('jobs/images', 'public');
        }

        Job::create($validatedData);
        return redirect()->route('jobs.index')->with('success', 'Job created successfully!');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::find($id);
        return view('backend.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $job = Job::find($id);
        return view('backend.jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($job->image) && Storage::disk('public')->exists($job->image)) {
                Storage::disk('public')->delete($job->image);
            }

            $validatedData['image'] = $request->file('image')->store('jobs/images', 'public');
        }

        $job->fill($validatedData);

        if (!$job->isDirty()) {
            return redirect()->route('jobs.index')->with('info', 'No changes were made.');
        }
        $job->save();
        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::find($id);

        if ($job) {
            // Delete the image from the 'public' disk
            if ($job->image) {
                Storage::disk('public')->delete($job->image);
            }

            // Delete the job record
            $job->delete();

            return redirect()->route('jobs.index')
                ->with('success', 'Job Detail deleted successfully');
        }

        return redirect()->route('jobs.index')
            ->with('error', 'Job not found');
    }
}
