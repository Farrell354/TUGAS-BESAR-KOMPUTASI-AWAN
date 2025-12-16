<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'tambal_ban_id', 'rating', 'komentar'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
