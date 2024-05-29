<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class BingoController extends Controller
{
    public function index()
    {
        $callHistory = Session::get('callHistory', []);
        $totalCalls = count($callHistory);
        $currentCall = end($callHistory);
        return view('bingo.index', compact('callHistory', 'totalCalls', 'currentCall'));
    }

    public function callNextNumber()
    {
        $newNumber = rand(1, 75);
        $callHistory = Session::get('callHistory', []);
        $callHistory[] = $newNumber;
        Session::put('callHistory', $callHistory);
        return redirect()->route('bingo.index');
    }

    public function resetBoard()
    {
        Session::forget('callHistory');
        return redirect()->route('bingo.index');
    }
}
