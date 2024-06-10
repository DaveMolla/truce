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

    // public function checkBoard(Request $request)
    // {
    //     $cardNumber = $request->input('name');
    //     $callHistory = Session::get('callHistory', []);
    //     $winningPatterns = Session::get('winning_pattern', []);
    //     $totalCalls = count($callHistory);
    //     $currentCall = end($callHistory);
    //     $gameId = Session::get('gameId');
    //     $winningPatterns = Session::get('winning_pattern', []);
    //     $availableNumbers = array_diff(range(1, 75), $callHistory);
    //     $numbersAvailable = !empty($availableNumbers);
    //     $branchUser = Auth::user();

    //     $game = Game::find($gameId);
    //     $totalBetAmount = $game->total_bet_amount;
    //     $profit = $game->profit;

    //     $winningAmount = $totalBetAmount - $profit;

    //     $winningP = WinningPattern::find($winningPatterns);
    //     // dd($winningP);
    //     $win = $winningP->pattern_data;

    //     if (is_string($win)) {
    //         $win = json_decode($win, true);
    //     }

    //     if (!is_array($win)) {
    //         $win = [];
    //     }

    //     $selectedNumbers = Session::get('selected_numbers', []);
    //     $availableNumbers = array_diff(range(1, 75), $callHistory);
    //     $numbersAvailable = !empty($availableNumbers);

    //     $bingoCard = BingoCard::find($cardNumber);

    //     if (!$bingoCard) {
    //         return redirect()->back()->withErrors(['The card number is not selected for this game.']);
    //     }
    //     $boardNumbers = json_decode($bingoCard->card_data, true);

    //     $isWinningPattern = false;
    //     foreach ($win as $pattern) {
    //         // dd($pattern);
    //         if (is_array($pattern) && count(array_intersect($callHistory, $pattern)) === count($pattern)) {
    //             $isWinningPattern = true;
    //             break;
    //         }
    //     }
    //     // dd($win);

    //     return view('bingo.index', compact('callHistory', 'totalCalls', 'currentCall', 'winningPatterns', 'winningAmount', 'numbersAvailable', 'boardNumbers', 'isWinningPattern', 'cardNumber', 'win', 'branchUser'));
    // }

    public function checkCard(Request $request)
    {
        $cardId = $request->input('card_id');
        $card = BingoCard::find($cardId);
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
                    $isCenter = $rowIndex == 2 && $colIndex == 2; // Check if it's the center spot
                    if (($number !== "FREE" && !$isCenter) && !in_array($number, $calledNumbers)) {
                        return false; // Exit if a number required by the pattern has not been called
                    }
                }
            }
        }
        return true;
    }

    // private function checkWinningPattern($patternGrid, $cardData, $calledNumbers)
    // {
    //     foreach ($patternGrid as $rowIndex => $row) {
    //         foreach ($row as $colIndex => $isActive) {
    //             if ($isActive) {
    //                 $number = $cardData[$rowIndex][$colIndex];
    //                 $isCenter = $rowIndex == 2 && $colIndex == 2; // Check if it's the center spot
    //                 if ($number !== "FREE" && !$isCenter && !in_array($number, $calledNumbers)) {
    //                     return false;
    //                 }
    //             }
    //         }
    //     }
    //     return true;
    // }

    // private function checkWinningPattern($patternGrids, $cardData, $calledNumbers)
    // {
    //     if ('winning_pattern' === 'All Common Patterns') {
    //         foreach ($patternGrids as $patternGrid) { // Loop through each pattern grid
    //             $patternMatched = true;
    //             foreach ($patternGrid as $rowIndex => $row) {
    //                 foreach ($row as $colIndex => $isActive) {
    //                     if ($isActive) {
    //                         $number = $cardData[$rowIndex][$colIndex] ?? null;
    //                         $isCenter = $rowIndex == 2 && $colIndex == 2; // Check if it's the center spot
    //                         if (($number !== "FREE" && !$isCenter) && !in_array($number, $calledNumbers)) {
    //                             $patternMatched = false;
    //                             break 2; // Exit both loops if a number required by the pattern has not been called
    //                         }
    //                     }
    //                 }
    //             }
    //             if ($patternMatched) {
    //                 return true; // If any pattern matches, return true immediately
    //             }
    //         }
    //         return false; // If no patterns match, return false
    //     }
    //     foreach ($patternGrids as $rowIndex => $row) {
    //         foreach ($row as $colIndex => $isActive) {
    //             if ($isActive) {
    //                 $number = $cardData[$rowIndex][$colIndex];
    //                 $isCenter = $rowIndex == 2 && $colIndex == 2; // Check if it's the center spot
    //                 if ($number !== "FREE" && !$isCenter && !in_array($number, $calledNumbers)) {
    //                     return false;
    //                 }
    //             }
    //         }
    //     }
    //     return true;

    // }






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
