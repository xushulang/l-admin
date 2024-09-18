<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Dashboard',
                'title' => 'Dashboard',
                'route' => 'admin.index',
                'icon' => 'MaterialSymbolsDashboardOutline',
                'menu' => true,
                'sort' => 0,
                'guard_name' => 'web',
            ],
            [
                'name' => 'Permission',
                'title' => 'Permission Manage',
                'route' => null,
                'icon' => 'IonCubeOutline',
                'menu' => true,
                'sort' => 0,
                'guard_name' => 'web',
                'children' => [
                    [
                        'name' => 'Permission List',
                        'title' => 'Permission List',
                        'route' => 'permission.index',
                        'icon' => 'MaterialSymbolsFingerprint',
                        'menu' => true,
                        'sort' => 0,
                        'guard_name' => 'web',
                    ],
                    [
                        'name' => 'Role List',
                        'title' => 'Role List',
                        'route' => 'role.index',
                        'icon' => 'FluentPersonBoard20Regular',
                        'menu' => true,
                        'sort' => 0,
                        'guard_name' => 'web',
                        'children' => [
                            [
                                'name' => 'Create Role',
                                'title' => 'Create Role',
                                'route' => 'role.store',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Update Role',
                                'title' => 'Update Role',
                                'route' => 'role.update',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Delete Role',
                                'title' => 'Delete Role',
                                'route' => 'role.destroy',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Role Permission',
                                'title' => 'Role Permission',
                                'route' => 'role.permission.index',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Role Permission Sync',
                                'title' => 'Role Permission Sync',
                                'route' => 'role.permission.sync',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Role User',
                                'title' => 'Role User',
                                'route' => 'role.user.index',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Role User Assign',
                                'title' => 'Role User Assign',
                                'route' => 'role.user.assign',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Role User Remove',
                                'title' => 'Role User Remove',
                                'route' => 'role.user.remove',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                        ],
                    ],
                    [
                        'name' => 'User List',
                        'title' => 'User List',
                        'route' => 'user.index',
                        'icon' => 'CarbonUser',
                        'menu' => true,
                        'sort' => 0,
                        'guard_name' => 'web',
                        'children' => [
                            [
                                'name' => 'Create User',
                                'title' => 'Create User',
                                'route' => 'user.store',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Update User',
                                'title' => 'Update User',
                                'route' => 'user.update',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                            [
                                'name' => 'Delete User',
                                'title' => 'Delete User',
                                'route' => 'user.destroy',
                                'icon' => 'MaterialSymbolsCircleOutline',
                                'menu' => false,
                                'sort' => 0,
                                'guard_name' => 'web',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Notification',
                'title' => 'Notification Center',
                'route' => 'notification.index',
                'icon' => 'IonNotificationsOutline',
                'menu' => true,
                'sort' => 0,
                'guard_name' => 'web',
                'children' => [
                    [
                        'name' => 'Read Notification',
                        'title' => 'Read Notification',
                        'route' => 'notification.read',
                        'icon' => 'MaterialSymbolsCircleOutline',
                        'menu' => false,
                        'sort' => 0,
                        'guard_name' => 'web',
                    ],
                    [
                        'name' => 'Empty Notification',
                        'title' => 'Empty Notification',
                        'route' => 'notification.empty',
                        'icon' => 'MaterialSymbolsCircleOutline',
                        'menu' => false,
                        'sort' => 0,
                        'guard_name' => 'web',
                    ],
                    [
                        'name' => 'Top Ten Notification',
                        'title' => 'Top Ten Notification',
                        'route' => 'notification.top.ten',
                        'icon' => 'MaterialSymbolsCircleOutline',
                        'menu' => false,
                        'sort' => 0,
                        'guard_name' => 'web',
                    ],
                ],
            ],
        ];

        $this->handleData($data);
    }

    private function handleData(array $data, int $parent_id = 0): void
    {
        foreach ($data as $value) {
            $temp = [
                'name' => $value['name'],
                'title' => $value['title'],
                'route' => $value['route'],
                'icon' => $value['icon'],
                'menu' => $value['menu'],
                'parent_id' => $parent_id,
                'sort' => $value['sort'],
                'guard_name' => $value['guard_name'],
            ];

            $permission = Permission::create($temp);

            if (isset($value['children'])) {
                $this->handleData($value['children'], $permission->id);
            }
        }
    }
}
