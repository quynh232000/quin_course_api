<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningLog extends Model
{
    use HasFactory;
    protected $table = "learning_logs";
    protected $fillable = [
        'user_id',
        'course_id',
        'current_step',
        'user_progress',
        'time_start',
        'is_completed'
    ];


    
}
