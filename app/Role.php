<?php

namespace App;

class Role extends \Spatie\Permission\Models\Role
{
    protected $fillable = [     
        'name', 'guard_name', 'created_by'
    ];
}
