<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Inertia\Inertia;


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
