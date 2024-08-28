<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = "settings";
    protected $fillable = [
        'type',
        'key',
        'value',
        'user_id'
    ];
    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at'
    ];
}

