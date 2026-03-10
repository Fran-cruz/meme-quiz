<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\Player;
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
//     Obtiene una sesión específica por su ID y devuelve la lista de jugadores asociados.
//     Se usa cuando se necesita consultar rápidamente quiénes están dentro de una sala.
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
//     Crea una nueva sesión de juego para un cuestionario específico.
//     Genera un código único de acceso, establece el estado inicial como "waiting"
//     y además limita la cantidad de sesiones activas, finalizando automáticamente las más antiguas.
    {
        $code = strtoupper(Str::random(6));

        // Create new session
        $session = GameSession::create([
            'quiz_id' => $quiz->id,
            'code' => $code,
            'status' => 'waiting',// La sesión inicia en espera hasta que el anfitrión la comience.
        ]);

        // Obtiene los IDs de las 2 sesiones más recientes que aún no han finalizado.
        $activeIds = GameSession::where('status', '!=', 'finished')
            ->orderByDesc('created_at')
            ->take(2)
            ->pluck('id');

        // Finaliza automáticamente las sesiones activas más antiguas que no estén entre las 2 más recientes.
        GameSession::whereNotIn('id', $activeIds)
            ->where('status', '!=', 'finished')
            ->update([
                'status' => 'finished',
                'ended_at' => now(),// Registra la fecha/hora de finalización automática.
            ]);

        return response()->json($session);
    }

    public function manage()
//     Carga las últimas sesiones creadas junto con su cuestionario y jugadores.
//     Se utiliza para mostrar una vista administrativa o de gestión de sesiones.
    {
        $sessions = GameSession::latest()->with('quiz', 'players')->take(10)->get();
        $quizzes = Quiz::orderBy('title')->get(['id', 'title']);

        return Inertia::render('Sessions/Manage', [
            'sessions' => $sessions,
            'quizzes' => $quizzes,
        ]);
    }

    // Erase all players from session
    public function erasePlayers(GameSession $session)
//     Elimina todos los jugadores asociados a una sesión específica.
//     Es útil para reiniciar una sala sin necesidad de eliminar la sesión completa.
    {
        $session->players()->delete();
        return redirect()->back()->with('success', 'Players erased successfully.');
    }

    // Close room manually (mark as playing)
    public function closeSession(GameSession $session)
//     Cambia manualmente el estado de una sesión a "closed".
//     Se usa para cerrar la sala e impedir nuevas acciones antes de finalizarla por completo.
    {
        $session->update(['status' => 'closed']);
        return redirect()->back()->with('success', 'Session closed.');
    }

    // Terminate session manually
    public function terminateSession(GameSession $session)
//     Finaliza manualmente una sesión de juego.
//     Además del cambio de estado, registra el momento exacto en que la sesión terminó.
    {
        $session->update(['status' => 'finished', 'ended_at' => now()]);
        return redirect()->back()->with('success', 'Session terminated.');
    }

    public function all()
//     Obtiene todas las sesiones registradas en orden descendente por fecha de creación.
//     También carga los jugadores de cada sesión para mostrarlos en una vista general.
    {
        $sessions = GameSession::with('players') // ← load players
        ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Sessions/All', [
            'sessions' => $sessions,
        ]);
    }

    public function start(GameSession $session)
//     Inicia oficialmente una sesión de juego.
//     Cambia su estado a "playing" y guarda la fecha/hora de inicio.
    {
        $session->update([
            'status' => 'playing',
            'started_at' => now(),// Guarda el instante en que la partida comenzó.
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
//     Devuelve los datos detallados de una sesión en formato JSON.
//     Incluye sus jugadores y las respuestas de cada uno.
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
//     Muestra una vista administrativa detallada de una sesión.
//     Calcula estadísticas por jugador, como respuestas correctas y total respondidas,
//     y luego ordena a los jugadores de mayor a menor puntaje.
    {
        $session->load('quiz', 'players.answers'); // use the correct relation name

        $players = $session->players->map(function ($player) {
            $player->correct_count = $player->answers->where('is_correct', true)->count();
            // Cuenta cuántas respuestas correctas tiene el jugador.

            $player->total_answered = $player->answers->count();
            // Cuenta el total de respuestas registradas del jugador.

            return $player;
        })->sortByDesc('correct_count'); // Ordena de mayor a menor

        return Inertia::render('Sessions/SessionDetail', [
            'session' => $session,
            'players' => $players->values(), // Reindexa la colección para enviarla limpia al frontend.
        ]);
    }

    // Add this method
    public function showManageJson(GameSession $session)
//     Devuelve en JSON la información detallada de una sesión y sus jugadores.
//     También calcula estadísticas de desempeño para cada jugador y las ordena por respuestas correctas.
    {
        $players = $session->players()
            ->with('answers')
            ->get()
            ->map(function ($player) {
                $player->correct_count = $player->answers->where('is_correct', true)->count();
                $player->total_answered = $player->answers->count();
                return $player;
            })
            ->sortByDesc('correct_count')
            ->values();

        return response()->json([
            'session' => $session->fresh(), // Recarga la sesión para obtener su estado más actualizado.
            'players' => $players,
        ]);
    }

    public function finished(GameSession $session)
//     Muestra la vista final de una sesión terminada.
//     Calcula el rendimiento de cada jugador y genera una clasificación ordenada por respuestas correctas.
    {
        $players = $session->players()
            ->with('answers')
            ->get()
            ->map(function ($player) {

                $player->correct_count =
                    $player->answers->where('is_correct', true)->count();

                $player->total_answered =
                    $player->answers->count();

                return $player;
            })
            ->sortByDesc('correct_count')
            ->values();

        return Inertia::render('Sessions/QuizFinished', [
            'session' => $session,
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
