<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Trainee;

class CheckTraineeSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('trainee_id')) {
            return redirect()->route('trainee.login')->with('error', 'Veuillez vous connecter pour accéder à l\'espace stagiaire.');
        }

        $trainee = Trainee::find($request->session()->get('trainee_id'));

        if (!$trainee) {
            $request->session()->forget('trainee_id');
            return redirect()->route('trainee.login')->with('error', 'Session invalide.');
        }

        // Share the trainee with views
        \View::share('currentTrainee', $trainee);

        return $next($request);
    }
}
