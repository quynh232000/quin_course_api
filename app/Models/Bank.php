<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = "banks";
    protected $fillable = [
        "name",
        "short_name",
        "symbol",
        "bin",
        "logo_url"
    ];
}
