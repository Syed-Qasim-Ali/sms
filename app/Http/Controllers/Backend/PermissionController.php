<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':permissions-list|permissions-create|permissions-edit|permissions-delete', ['only' => ['index', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':permissions-create', ['only' => ['create', 'store']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':permissions-edit', ['only' => ['edit', 'update']]);
        $this->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':permissions-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        return view('backend.permissions.index');
    }
}
