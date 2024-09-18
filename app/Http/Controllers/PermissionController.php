<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permission
     */
    public function index(): Response
    {
        $permissions = Cache::tags('permission')->remember('permissions', 86400, function () {
            return $this->getTree(Permission::orderBy('sort', 'asc')->orderBy('id', 'asc')->get(['id', 'name', 'title', 'route', 'icon', 'parent_id', 'menu', 'guard_name'])->toArray());
        });

        return Inertia::render('Permission/Index', compact('permissions'));
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
