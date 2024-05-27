<?php

// app/Http/Controllers/BingoCardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BingoCardsImport;

class BingoCardController extends Controller
{
    public function showImportForm()
    {
        return view('branch.import');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new BingoCardsImport, $request->file('file'));

        return redirect()->route('admin.dashboard')->with('success', 'Bingo cards imported successfully!');
    }
}
