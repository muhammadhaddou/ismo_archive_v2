<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function index()
    {
        $movements = Movement::with('document.trainee', 'user')
            ->latest('date_action')
            ->paginate(20);
        return view('movements.index', compact('movements'));
    }

    public function today()
    {
        $movements = Movement::with('document.trainee', 'user')
            ->whereDate('date_action', today())
            ->latest('date_action')
            ->paginate(20);
        return view('movements.today', compact('movements'));
    }
}