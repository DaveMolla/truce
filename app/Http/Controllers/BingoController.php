<?php

namespace App\Http\Controllers;

use App\Models\BingoCard;
use App\Models\Branch;
use App\Models\Game;
use App\Models\WinningPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class BingoController extends Controller
{
    public function index()
    {
        $callHistory = Session::get('callHistory', []);
        $totalCalls = count($callHistory);
        $currentCall = end($callHistory);
        $gameId = Session::get('gameId');
        $winningPatterns = json_decode(Session::get('winning_pattern', '[]'), true);
        // dd($winningPatterns);
        $availableNumbers = array_diff(range(1, 75), $callHistory);
        $numbersAvailable = !empty($availableNumbers);

        $winningP =WinningPattern::find($winningPatterns);
        // dd($winningP);
        $win = $winningP->pattern_data;

        $branchUser = Auth::user();

        $game = Game::find($gameId);
        $totalBetAmount = $game->total_bet_amount;
        $profit = $game->profit;

        $winningAmount = $totalBetAmount - $profit;


        return view('bingo.index', compact('callHistory', 'totalCalls', 'currentCall', 'winningPatterns', 'gameId', 'numbersAvailable', 'winningAmount', 'win','branchUser'));
    }

    public function callNextNumber()
    {
        $callHistory = Session::get('callHistory', []);
        $newNumber = $this->getUniqueNumber($callHistory);

        if ($newNumber !== null) {
            $callHistory[] = $newNumber;
            Session::put('callHistory', $callHistory);
        }

        return redirect()->route('bingo.index');
    }

    private function getUniqueNumber($callHistory)
    {
        $availableNumbers = array_diff(range(1, 75), $callHistory);

        if (empty($availableNumbers)) {
            return null;
        }

        return $availableNumbers[array_rand($availableNumbers)];
    }

    public function resetBoard()
    {
        Session::forget('callHistory');
        return redirect()->route('bingo.index');
    }

    public function checkBoard(Request $request)
    {
        $cardNumber = $request->input('name');
        $callHistory = Session::get('callHistory', []);
        $winningPatterns = Session::get('winning_pattern', []);
        $totalCalls = count($callHistory);
        $currentCall = end($callHistory);
        $gameId = Session::get('gameId');
        $winningPatterns = Session::get('winning_pattern', []);
        $availableNumbers = array_diff(range(1, 75), $callHistory);
        $numbersAvailable = !empty($availableNumbers);
        $branchUser = Auth::user();

        $game = Game::find($gameId);
        $totalBetAmount = $game->total_bet_amount;
        $profit = $game->profit;

        $winningAmount = $totalBetAmount - $profit;

        $winningP =WinningPattern::find($winningPatterns);
        // dd($winningP);
        $win = $winningP->pattern_data;

        if (is_string($win)) {
            $win = json_decode($win, true);
        }

        if (!is_array($win)) {
            $win = [];
        }

        $selectedNumbers = Session::get('selected_numbers', []);
        $availableNumbers = array_diff(range(1, 75), $callHistory);
        $numbersAvailable = !empty($availableNumbers);

        $bingoCard = BingoCard::find($cardNumber);

        if (!$bingoCard) {
            return redirect()->back()->withErrors(['The card number is not selected for this game.']);
        }
        $boardNumbers = json_decode($bingoCard->card_data, true);

        $isWinningPattern = false;
        foreach ($win as $pattern) {
            // dd($pattern);
            if (is_array($pattern) && count(array_intersect($callHistory, $pattern)) === count($pattern)) {
                $isWinningPattern = true;
                break;
            }
        }
        // dd($win);

        return view('bingo.index', compact('callHistory', 'totalCalls', 'currentCall', 'winningPatterns', 'winningAmount', 'numbersAvailable', 'boardNumbers', 'isWinningPattern', 'cardNumber', 'win','branchUser'));
    }

}