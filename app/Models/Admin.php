<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'total_approved',
        'today_approved'
    ];

    public function role ()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public static function checkAccess ($id, $keyword)
    {
        $admin = Admin::find($id);
        if($admin->role_id == 0) //SuperAdmin
        {
            return true;
        }
        $role_json = json_decode($admin->role->role_json);
        if (in_array($keyword, $role_json)) {
            return true;
        }
        return false;
    }

    public function getTotalApprovedAttribute()
    {
        return ShowroomVerification::where('admin_id', $this->id)->count();
    }

    public function getTodayApprovedAttribute()
    {
        return ShowroomVerification::where('admin_id', $this->id)->whereDate('created_at', Carbon::today())->count();
    }


}
