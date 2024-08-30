<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comments";
    protected $fillable = [
        'comment',
        'type',
        'user_id',
        'commentable_id',
        'is_approved',
        'is_deleted',
        'is_answered'
    ];
}
