<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'phone',
        'DOB',
        'city_id',
    ];

    public function studentHasCity(){
        return $this->hasOne('App\Models\City', 'city_id', 'city_id');
    }
}
