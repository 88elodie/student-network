<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_location',
        'file_type',
        'user_id',
    ];

    public function documentHasUser(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
