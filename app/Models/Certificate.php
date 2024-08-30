<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $table = 'certificates';
    protected $fillable = [
        'uuid',
        'user_id',
        'course_id',
        'image_url',
        'name',
        'download_count',
        'share_count'
    ];
}
