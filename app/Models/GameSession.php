<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    /** @use HasFactory<\Database\Factories\GameSessionFactory> */
    use HasFactory;

    protected $fillable = ['quiz_id', 'code', 'status', 'current_question_id', 'started_at', 'ended_at'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function currentQuestion()
    {
        return $this->belongsTo(Question::class, 'current_question_id');
    }
}
