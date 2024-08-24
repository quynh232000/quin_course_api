<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;
    protected $table = "course_sections";

    protected $fillable = [
        'uuid',
        'course_id',
        'title',
        'will_learn',
        'priority',
        'is_show'
    ];
    public function steps(){
        return $this->hasMany(CourseStep::class, 'section_id');
    }
    public function first_step(){
        return $this->steps()->orderBy('created_at', 'asc')->first();
    }
    public function total_steps(){
        return $this->steps()->count()??0;
    }
    public function total_duration(){
        $total_duration = 0;
        foreach ($this->steps as $step){
            $total_duration += $step->duration;
        }
        return $total_duration;
    }
}
