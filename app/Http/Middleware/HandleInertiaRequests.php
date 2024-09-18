<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $data = [];

        if ($request->session()->exists('success')) {
            $data['flash']['status'] = 'success';
            $data['flash']['message'] = $request->session()->get('success');
        }

        if ($request->session()->exists('error')) {
            $data['flash']['status'] = 'error';
            $data['flash']['message'] = $request->session()->get('error');
        }

        if ($request->session()->exists('warning')) {
            $data['flash']['status'] = 'warning';
            $data['flash']['message'] = $request->session()->get('warning');
        }

        $data['lang'] = app()->getLocale();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            ...$data,
        ];
    }
}
