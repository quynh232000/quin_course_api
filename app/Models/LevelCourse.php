<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelCourse extends Model
{
    use HasFactory;
    protected $table = "levels";

    protected $fillable = [
        'name',
        'description',
        'slug'
    ];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
}
