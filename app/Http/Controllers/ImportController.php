<?php

namespace App\Http\Controllers;

use App\Imports\TraineesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('trainees.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        Excel::import(new TraineesImport, $request->file('file'));

        return redirect()->route('trainees.index')
            ->with('success', 'Importation réussie!');
    }
}