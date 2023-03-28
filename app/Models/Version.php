<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;

    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function model()
    {
        return $this->belongsTo(Models::class, 'model_id', 'id');
    }
}
