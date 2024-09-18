<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    /**
     * Renders the admin index page with server information.
     */
    public function index(): Response
    {
        $serverInfo = [
            'Server' => PHP_OS,
            'Laravel Framework' => app()->version(),
            'Environment' => $_SERVER['SERVER_SOFTWARE'],
            'PHP' => PHP_VERSION,
            'MySQL' => ((array)(DB::select('select version()')[0]))['version()']
        ];

        return Inertia::render('Admin/Index', compact('serverInfo'));
    }
}
