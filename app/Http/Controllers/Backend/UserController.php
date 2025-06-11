<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Mail\UserInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':users-list|users-create|users-edit|users-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':users-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':users-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':users-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) {
         $data = User::orderBy('id', 'DESC')->get();
        return view('backend.users.index', compact('data'))
            ->with('i', 0);
        }else{
        $data = User::where('w
        awid', $user->id)->orderBy('id', 'DESC')->get();
        return view('backend.users.index', compact('data'))
            ->with('i', 0);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('backend.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_num' => 'required|string|max:20',
            'password' => 'required|confirmed',
            'roles' => 'required',
            'status' => 'required'
        ]);
        $planpassword = $validatedData['password'];
        $user = User::create([
            'name' => $validatedData['name'],
            'lname' => $validatedData['lname'],
            'email' => $validatedData['email'],
            'phone_num' => $validatedData['phone_num'],
            'password' => Hash::make($planpassword),
            'status' => $validatedData['status'],
        ]);
        $user->assignRole($validatedData['roles']);
        if ($request->has('invited')) {
            try {
                $inviteLink = route('login', ['email' => $user->email]);
                Mail::to($user->email)->send(new UserInvitationMail($user, $inviteLink, $planpassword));
            } catch (\Exception $e) {
                return redirect()->route('users.index')
                    ->with('error', 'User created but email sending failed: ' . $e->getMessage());
            }
        }
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('backend.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Status toggle karna
        $user->status = $user->status === 'pending' ? 'active' : 'pending';
        $user->save();
        return response()->json([
            'success' => true,
            'new_status' => $user->status
        ]);
    }
}
