<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comments";
    protected $fillable = [
        'comment',
        'type',
        'user_id',
        'commentable_id',
        'is_approved',
        'is_deleted',
        'is_answered'
    ];
    public function commentor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reactions()
    {
        if ($this->type == 'blog') {
            $type = 'blog';
        } else {
            $type = 'comment';
        }
        return Reaction::where(['commentable_id' => $this->id, 'commentable_type' => 'comment'])->get() ?? [];
    }
    public function replies()
    {
        return Comment::where(['commentable_id' => $this->id, 'type' => 'comment'])->get() ?? [];
    }
    public function replies_count()
    {
        return Comment::where(['commentable_id' => $this->id, 'type' => 'comment'])->count() ?? 0;
    }
    public function reaction_count()
    {
        return count($this->reactions()) ?? 0;
    }
    public function is_reaction($type)
    {

        if (auth('api')->check()) {
            $checkReaction = Reaction::where(
                [
                    'user_id' => auth('api')->id(),
                    'commentable_id' => $this->id,
                    'commentable_type' => 'comment'
                ]
            )->first();
            if ($checkReaction) {
                return $checkReaction;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function all_type_reactions($type)
    {
        return Reaction::select('type')
            ->where(['commentable_id' => $this->id, 'commentable_type' => 'comment'])
            ->distinct()
            ->groupBy('type')
            ->get() ?? [];
    }


}
