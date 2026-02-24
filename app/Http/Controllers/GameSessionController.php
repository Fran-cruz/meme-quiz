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
        $session->update(['status' => 'playing', 'ended_at' => now()]);
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
        /*$sessions = GameSession::with('players')->get();
        return Inertia::render('Sessions/All', ['sessions' => $sessions]);*/
        $sessions = GameSession::with('players')
            ->withCount('players')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Sessions/All', [
            'sessions' => $sessions,
        ]);
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
        return response()->json($session);
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
