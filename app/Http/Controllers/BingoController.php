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

        $availableNumbers = array_diff(range(1, 75), $callHistory);
        $numbersAvailable = !empty($availableNumbers);

        $winningP = WinningPattern::find($winningPatterns);
        $win = json_decode($winningP->pattern_data, true);  // Ensure $win is an array
        // $winningPatternId = $request->input('winning_pattern');
        $winningPattern = WinningPattern::with('images')->find($winningPatterns);

        $branchUser = Auth::user();

        $game = Game::find($gameId);
        $totalBetAmount = $game->total_bet_amount;
        $profit = $game->profit;

        $winningAmount = $totalBetAmount - $profit;

        return view('bingo.index', compact('callHistory', 'totalCalls', 'currentCall', 'winningPatterns', 'gameId', 'numbersAvailable', 'winningAmount', 'win', 'branchUser', 'winningPattern'));
    }


    public function callNextNumber()
    {
        $callHistory = Session::get('callHistory', []);
        $newNumber = $this->getUniqueNumber($callHistory);

        if ($newNumber !== null) {
            $callHistory[] = $newNumber;
            Session::put('callHistory', $callHistory);
        }

        return response()->json([
            'number' => $newNumber,
            'totalCalls' => count($callHistory),
            'callHistory' => $callHistory
        ]);
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
    public function endGame(Request $request)
    {
        $gameId = Session::get('gameId');
        $callHistory = Session::get('callHistory', []);
        $totalCalls = count($callHistory);
        $game = Game::find($gameId);
        $game->update([
            'total_calls' => $totalCalls ?? '0',
            'status' => 'end',
        ]);
        return redirect()->route('branch.dashboard');
    }


    public function checkCard(Request $request)
    {
        $cardId = $request->input('card_id');
        $selectedNumbers = session('selected_numbers', '');

        // \Log::info('Retrieved selected numbers:', ['selectedNumbers' => $selectedNumbers]);

        if (!is_array($selectedNumbers)) {
            $selectedNumbers = explode(',', $selectedNumbers);
        }

        // \Log::info('After conversion, selected numbers:', ['selectedNumbers' => $selectedNumbers]);

        if (!in_array($cardId, $selectedNumbers)) {
            return back()->with('error', "Card number {$cardId} is not registered for this game.");
        }

        $card = BingoCard::find($cardId);

        if (!$card) {
            return back()->with('error', 'Card number does not exist.');
        }
        $calledNumbers = session('callHistory', []);
        $winningPattern = json_decode(Session::get('winning_pattern', '[]'), true);
        $winningP = WinningPattern::find($winningPattern);
        $isAllCommonPatterns = ($winningP->name === 'All Common Patterns');

        $win = json_decode($winningP->pattern_data, true);
        // \Log::info("Winning Pattern Indices: " . json_encode($win));

        $hasWon = $this->checkWinningPattern($win, json_decode($card->card_data), $calledNumbers, $isAllCommonPatterns);

        return back()->with([
            'show_modal' => true,
            'card_data' => json_decode($card->card_data),
            'called_numbers' => $calledNumbers,
            'has_won' => $hasWon,
            'card_id' => $cardId

        ]);
    }

    private function checkWinningPattern($patterns, $cardData, $calledNumbers, $isAllCommonPatterns)
    {
        if ($isAllCommonPatterns) {
            foreach ($patterns as $patternGrid) { // Assume $patterns is an array of pattern grids for 'All Common Patterns'
                if ($this->isPatternMatched($patternGrid, $cardData, $calledNumbers)) {
                    return true; // If any pattern matches, return true immediately
                }
            }
            return false; // If no patterns match, return false
        } else {
            // Assuming $patterns is a single pattern grid for a single pattern
            return $this->isPatternMatched($patterns, $cardData, $calledNumbers);
        }
    }

    private function isPatternMatched($patternGrid, $cardData, $calledNumbers)
    {
        foreach ($patternGrid as $rowIndex => $row) {
            foreach ($row as $colIndex => $isActive) {
                if ($isActive) {
                    $number = $cardData[$rowIndex][$colIndex] ?? null;
                    $isCenter = $rowIndex == 2 && $colIndex == 2;
                    if (($number !== "FREE" && !$isCenter) && !in_array($number, $calledNumbers)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function fetchCard(Request $request)
    {
        $cardId = $request->input('card_id');
        $card = BingoCard::find($cardId);

        if ($card) {
            return response()->json([
                'success' => true,
                'card' => $card
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Card not found'
            ]);
        }
    }


}
