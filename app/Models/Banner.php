<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $table = "banners";

    protected $fillable = [
        'title',
        'description',
        'alt',
        'from',
        'user_id',
        'placement',
        'link_to',
        'banner_url',
        'is_blank',
        'type',
        'priority',
        'is_show',
        'expired_at',
    ];
}
