<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;

class TraineeAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'cef' => 'required',
            'cin' => 'required'
        ]);

        $trainee = Trainee::where('cef', trim($request->cef))
            ->where('cin', trim($request->cin))
            ->first();

        if ($trainee) {
            $request->session()->put('trainee_id', $trainee->id);
            return redirect()->route('trainee.dashboard');
        }

        return back()->with('error', 'CEF ou CIN incorrect.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('trainee_id');
        return redirect()->route('trainee.login');
    }
}
