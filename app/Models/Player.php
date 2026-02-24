<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    protected $fillable = ['game_session_id', 'nickname', 'score', 'is_connected'];

    public function gameSession()
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function answers()
    {
        return $this->hasMany(PlayerAnswer::class);
    }
}
