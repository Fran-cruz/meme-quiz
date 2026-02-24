<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerAnswer extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerAnswerFactory> */
    use HasFactory;

    protected $fillable = ['player_id', 'question_id', 'answer_id', 'response_time'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
