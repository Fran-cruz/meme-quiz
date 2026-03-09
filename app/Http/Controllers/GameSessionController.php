<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\Http\Request;

use App\Models\Quiz;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GameSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function players($sessionId)
    {
        $session = GameSession::findOrFail($sessionId);
        return $session->players; // returns array of Player models
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Quiz $quiz)
    {
        $code = strtoupper(Str::random(6));

        // Create new session
        $session = GameSession::create([
            'quiz_id' => $quiz->id,
            'code' => $code,
            'status' => 'waiting',
        ]);

        // Get latest 2 sessions that are NOT finished
        $activeIds = GameSession::where('status', '!=', 'finished')
            ->orderByDesc('created_at')
            ->take(2)
            ->pluck('id');

        // Finish older sessions
        GameSession::whereNotIn('id', $activeIds)
            ->where('status', '!=', 'finished')
            ->update([
                'status' => 'finished',
                'ended_at' => now(),
            ]);

        return response()->json($session);
    }

    public function manage()
    {
        // Load all sessions (maybe latest 10)
        $sessions = GameSession::latest()->with('quiz', 'players')->take(10)->get();
        return Inertia::render('Sessions/Manage', [
            'sessions' => $sessions,
        ]);
    }

    // Erase all players from session
    public function erasePlayers(GameSession $session)
    {
        $session->players()->delete();
        return redirect()->back()->with('success', 'Players erased successfully.');
    }

    // Close room manually (mark as playing)
    public function closeSession(GameSession $session)
    {
        $session->update(['status' => 'closed']);
        return redirect()->back()->with('success', 'Session closed.');
    }

    // Terminate session manually
    public function terminateSession(GameSession $session)
    {
        $session->update(['status' => 'finished', 'ended_at' => now()]);
        return redirect()->back()->with('success', 'Session terminated.');
    }

    public function all()
    {
        $sessions = GameSession::with('players') // ← load players
        ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Sessions/All', [
            'sessions' => $sessions,
        ]);
    }

    public function start(GameSession $session)
    {
        $session->update([
            'status' => 'playing',
            'started_at' => now(),
        ]);

        return back();
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
    public function show(GameSession $session)
    {
        $session->load([
            'players.answers' // load each player's answers
        ]);

        return response()->json([
            'id' => $session->id,
            'code' => $session->code,
            'status' => $session->status,
            'started_at' => $session->started_at,
            'ended_at' => $session->ended_at,
            'players' => $session->players,
        ]);
    }

    public function showManage(GameSession $session)
    {
        $session->load('quiz', 'players.answers'); // use the correct relation name

        $players = $session->players->map(function ($player) {
            $player->correct_count = $player->answers->where('is_correct', true)->count();
            return $player;
        })->sortByDesc('correct_count');

        return Inertia::render('Sessions/SessionDetail', [
            'session' => $session,
            'players' => $players->values(),
        ]);
    }

    // Add this method
    public function showManageJson(GameSession $session)
    {
        $players = $session->players()
            ->with('answers')
            ->get()
            ->map(function ($player) {
                $player->correct_count = $player->answers->where('is_correct', true)->count();
                return $player;
            })
            ->sortByDesc('correct_count')
            ->values();

        return response()->json([
            'session' => $session->fresh(),
            'players' => $players,
        ]);
    }

    public function finished(GameSession $session)
    {
        $players = $session->players()
            ->with('answers')
            ->get()
            ->map(function ($player) {
                $player->correct_count = $player->answers
                    ->where('is_correct', true)
                    ->count();
                return $player;
            })
            ->sortByDesc('correct_count')
            ->values();

        return Inertia::render('Sessions/QuizFinished', [
            'players' => $players,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameSession $gameSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameSession $gameSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameSession $gameSession)
    {
        //
    }
}
