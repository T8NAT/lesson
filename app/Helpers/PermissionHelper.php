<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('userHasPermission')) {
    function userHasPermission($module, $action)
    {
        $permissionsList = [];

        foreach (Auth::user()->role->permissions as $permission) {
            $permissionsList = array_merge($permissionsList, $permission['permissions'] ?? []);
        }

        return isset($permissionsList[$module][$action]);
    }
}
