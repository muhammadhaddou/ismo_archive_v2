<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\Document;
use App\Models\Movement;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_stagiaires'  => Trainee::count(),
            'bac_temp_out'      => Document::where('type', 'Bac')
                                           ->where('status', 'Temp_Out')
                                           ->count(),
            'diplomes_prets'    => Document::where('type', 'Diplome')
                                           ->where('status', 'Stock')
                                           ->count(),
            'mouvements_today'  => Movement::whereDate('date_action', today())
                                           ->count(),
        ];

        $recent_movements = Movement::with(['document.trainee', 'user'])
                                    ->latest('date_action')
                                    ->take(10)
                                    ->get();

        $bac_alerts = Document::with('trainee')
                              ->where('type', 'Bac')
                              ->where('status', 'Temp_Out')
                              ->get();

        return view('dashboard', compact('stats', 'recent_movements', 'bac_alerts'));
    }
}