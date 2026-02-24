<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /** @use HasFactory<\Database\Factories\AnswerFactory> */
    use HasFactory;

    protected $fillable = ['question_id', 'answer', 'meme', 'sound'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function playerAnswers()
    {
        return $this->hasMany(PlayerAnswer::class);
    }
}
