<?php

namespace App;

class Permission extends \Spatie\Permission\Models\Permission
{
    public static function defaultPermissions()
    {
        return [
            'users-list',
            'users-create',
            'users-edit',
            'users-delete',

            
        ];
    }
}
