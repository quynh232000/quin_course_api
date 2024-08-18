<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacherinfo extends Model
{
    use HasFactory;
    protected $table = "teacherinfos";

    protected $fillable = [
        'user_id',
        'bank_id',
        'card_number',
        'momo_number'
    ];
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
