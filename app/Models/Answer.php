<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $table = "answers";

    protected $fillable = [
        'parent_id',
        'content',
        'explain',
        'from',
        'is_correct'
    ];
    protected $hidden = [
        "created_at",
        'updated_at',
        'explain',
        'is_correct'
    ];
}
