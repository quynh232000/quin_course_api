<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = "questions";

    protected $fillable = [
        'content',
        'parent_id',
        'from'
    ];
    protected $hidden = [
        "created_at",
        'updated_at'
    ];
}
