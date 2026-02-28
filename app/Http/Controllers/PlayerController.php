<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\GameSession;
use App\Models\Question;
use App\Models\Answer;
use App\Models\PlayerAnswer;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\File;


class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function join(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string',
            'code' => 'required|string',
        ]);

        // Fetch the session AFTER validating the input
        $session = GameSession::where('code', $request->code)->first();

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        // Check if the session is finished
        if ($session->status === 'finished') {
            return response()->json([
                'message' => 'This game has ended.'
            ], 422); // 422 Unprocessable Content is fine for validation-like errors
        }

        // Check if the session is playing
        if ($session->status === 'playing') {
            return response()->json([
                'message' => 'This game has started.'
            ], 422); // 422 Unprocessable Content is fine for validation-like errors
        }

        // Check if the session is closed
        if ($session->status !== 'waiting') {
            return back()->withErrors([
                'code' => 'This session is not accepting players.'
            ]);
        }

        // Create the player
        $player = Player::create([
            'nickname' => $request->nickname,
            'game_session_id' => $session->id,
        ]);

        return response()->json($player);
    }

    public function showJoinPage()
    {
        return Inertia::render('Player/Join');
    }

    public function wait(Player $player)
    {
        $player->load('gameSession');

        return Inertia::render('Player/Wait', [
            'player' => $player,
            'session' => $player->gameSession,
        ]);
    }

    public function questions(Player $player)
    {
        $player->load('gameSession');

        return Inertia::render('Player/Questions', [
            'player' => $player,
            'session' => $player->gameSession,
        ]);
    }

    public function questionsData(Player $player)
    {
        $session = $player->gameSession;

        $questions = Question::with(['answers' => function ($query) {
            $query->select('id', 'question_id', 'answer');
        }])
            ->orderBy('order')
            ->get(['id', 'question', 'image', 'time_limit']);

        return response()->json([
            'questions' => $questions,
        ]);
    }

    public function submitAnswer(Request $request, Player $player)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required|exists:answers,id',
            'response_time' => 'required|numeric|min:0'
        ]);

        if (PlayerAnswer::where('player_id', $player->id)
            ->where('question_id', $request->question_id)
            ->exists()) {

            return response()->json([
                'error' => 'Already answered'
            ], 400);
        }

        $answer = Answer::where('id', $request->answer_id)
            ->where('question_id', $request->question_id)
            ->firstOrFail();

        $isCorrect = $answer->is_correct;

        // Store player answer
        PlayerAnswer::create([
            'player_id' => $player->id,
            'question_id' => $request->question_id,
            'answer_id' => $request->answer_id,
            'is_correct' => $isCorrect,
            'response_time' => $request->response_time
        ]);

        // Load memes.json
        $memes = json_decode(File::get(resource_path('data/memes.json')), true);

        $memeType = $isCorrect ? 'good' : 'bad';

        $randomMeme = $memes[$memeType][array_rand($memes[$memeType])];

        return response()->json([
            'is_correct' => $isCorrect,
            'meme' => $randomMeme,
            'sound' => $memeType // frontend decides which file to play
        ]);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        //
    }
}
