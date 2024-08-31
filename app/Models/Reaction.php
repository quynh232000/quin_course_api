<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;
    protected $table = "reactions";
    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'type'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
