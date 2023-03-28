<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public static $roleRights = [
        'dashboard' => 'Dashboard',
        'roles' => 'Roles',
        'settings' => 'Settings',
        'admin' => 'Admin Users',
    ];

    protected $guarded = [];



}
