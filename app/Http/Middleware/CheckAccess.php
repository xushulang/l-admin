<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class CheckAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        $currentRouteName = Route::currentRouteName();
        $permission = Permission::where('route', $currentRouteName)->first();

        if (!$permission) {
            return Inertia::render('Result/404');
        }

        if ($user->can($permission->name)) {
            $currentLocation = Cache::tags('currentLocation')->get('currentLocation' . '_' . md5($currentRouteName));

            if (!$currentLocation) {
                $currentLocation = $this->getCurrentLocation($permission->name);
                Cache::tags('currentLocation')->put('currentLocation' . '_' . md5($currentRouteName), $currentLocation, 86400);
            }

            $notificationCount = $user->unreadNotifications()->count();

            $data = [
                'notificationCount' => $notificationCount,
                'currentLocation' => $currentLocation,
            ];

            $menus = $request->session()->get('menus');

            if (!$menus) {
                if ($user->hasRole('Super Admin')) {
                    $menus = $this->getTree(Permission::where('menu', 1)->orderBy('sort', 'asc')->orderBy('id', 'asc')->get()->toArray());
                } else {
                    $menus = $this->getTree($user->getAllPermissions()->where('menu', 1)->sortBy('sort')->sortBy('id')->toArray());
                }

                $request->session()->put('menus', $menus);
            }

            $data['menus'] = $menus;

            Inertia::share($data);
        } else {
            return Inertia::render('Result/403');
        }

        return $next($request);
    }

    /**
     * Get current location.
     */
    public function getCurrentLocation(string $name, array &$currentLocation = []): array
    {
        while ($current = Permission::where('name', $name)->first()) {
            array_unshift($currentLocation, $current);
            $name = $current->parent_id ? Permission::find($current->parent_id)->name : null;
        }

        return $currentLocation;
    }

    /**
     * Transform array to a tree structure.
     * 将数组转化为树状结构
     */
    public function getTree(array $array, $parent_id = 0, $level = 0, $levelLimit = null, $idName = 'id', $parentIDName = 'parent_id'): array
    {
        $list = [];

        foreach ($array as $key => $value) {
            if ($value[$parentIDName] == $parent_id) {
                $value['level'] = $level;
                unset($array[$key]);

                if ($levelLimit == null || $level < $levelLimit) {
                    $temp = $this->getTree($array, $value[$idName], $level + 1, $levelLimit, $idName, $parentIDName);

                    if ($temp) {
                        $value['children'] = $temp;
                    }
                }

                $list[] = $value;
            }
        }

        return $list;
    }
}
