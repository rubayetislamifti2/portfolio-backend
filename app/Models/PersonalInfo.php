<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'academic_qualification',
        'experience',
        'skills',
    ];

    protected $casts = [
        'phone'=>'array',
        'address'=>'array',
        'academic_qualification'=>'array',
        'experience'=>'array',
        'skills'=>'array',
    ];
}
