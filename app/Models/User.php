<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $table = "users";
    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function isVerifyTeacher()
    {
        return Teacherinfo::where('user_id', auth('admin')->user()->id)->exists();
    }
    public function roles()
    {
        $role_ids = UserRole::where('user_id', $this->id)->pluck('role_id');
        return Role::whereIn('id', $role_ids)->pluck('name');
    }
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'full_name',
        'username',
        'email',
        'phone_number',
        'avatar_url',
        'thumbnail_url',
        'birthday',
        'address',
        'password',
        'is_blocked',
        'is_pro',
        'is_comment_blocked',
        'comment_blocked_at',
        'is_learn_tour_completed',
        'is_onboarding_completed',
        'remember_token',
        'bio',
        'email_verified_at',
        'failed_attempts',
        'blocked_until'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isEnrolled($couse_id): bool
    {
        return Enrollment::where(['user_id' => $this->id, 'course_id' => $couse_id])->exists();
    }
}
