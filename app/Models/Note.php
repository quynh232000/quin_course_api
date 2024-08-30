<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $table = "notes";
    protected $fillable = [
        'note',
        'user_id',
        'step_id',
        'time'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
    ];
    public function step(){
        return $this->belongsTo(CourseStep::class,'step_id');   
    }
}
