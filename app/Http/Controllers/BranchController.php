<?php

// app/Http/Controllers/BranchController.php

namespace App\Http\Controllers;

use App\Models\BingoCard;
use App\Models\Branch;
use App\Models\Game;
use App\Models\WinningPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class BranchController extends Controller
{
    /**
     * Show the branch login form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('branch.login');
    }

    public function register()
    {
        return view('branch.register');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|unique:users,phone',
            'address' => 'required',
            'password' => 'required|min:6',
        ]);

        $branchUser = User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'] ?? 'branch',
        ]);

        $branch = Branch::create([
            'user_id' => $branchUser->id,
        ]);

        return redirect()->route('admin.manage-branch-account')->with('success', 'branch registered successfully!');
    }

    /**
     * Handle a branch login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->role === 'branch') {
                Auth::login($user);
                return redirect()->intended(route('branch.dashboard'));
            } else {
                return back()->withErrors([
                    'phone' => 'You do not have permission to access this area.',
                ]);
            }
        }

        return back()->withErrors([
            'phone' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the branch out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the branch dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $bingoCards = BingoCard::all();
        $winningPatterns = WinningPattern::all();
        return view('branch.dashboard', compact('bingoCards', 'winningPatterns'));
    }
    public function history(Request $request)
    {
        // $recentGames = Game::latest()->paginate(10);
        $branchUser = Auth::user();
        // $games = Game::where('branch_user_id', $branchUser->id)->first();

        // $branch_user = Branch::where('user_id', Auth::user()->id)->first();
        // $branches = Branch::where('agent_id', $agent->id)->get();
        // dd($games);
        $recentGames = Game::where('branch_user_id', $branchUser->id)->latest()->paginate(10);

        $totalGames = Game::where('branch_user_id', $branchUser->id)->count();

        $branch = Branch::where('user_id', $branchUser->id)->first();

        $walletBalance = $branchUser->current_balance ? $branchUser->current_balance : 0;

        return view('branch.history', compact('totalGames', 'walletBalance', 'branchUser', 'recentGames'));
    }

    // public function createGame(Request $request)
    // {
    //     $request->validate([
    //         'bet_amount' => 'required|integer|min:1',
    //         'number_of_selected_numbers' => 'required|integer|min:1',
    //         'selected_numbers' => 'required|string',
    //         'winning_pattern' => 'required|exists:winning_patterns,id',
    //     ]);

    //     $totalBetAmount = $request->bet_amount * $request->number_of_selected_numbers;

    //     Game::create([
    //         'bet_amount' => $request->bet_amount,
    //         'total_players' => $request->number_of_selected_numbers,
    //         'total_calls' => 0, // Assuming this starts at 0
    //         'status' => 'pending',
    //         'total_bet_amount' => $totalBetAmount,
    //         'profit' => 0, // Assuming profit calculation is done elsewhere
    //     ]);

    //     return redirect()->route('branch.game-page')->with('success', 'Game created successfully!');
    // }

    // public function createGame(Request $request)
    // {
    //     $validated = $request->validate([
    //         'bet_amount' => 'required|numeric',
    //         'selected_numbers' => 'required|string',
    //         'winning_pattern' => 'required|integer',
    //     ]);

    //     $selectedNumbers = explode(',', $validated['selected_numbers']);
    //     $numberOfSelectedNumbers = count($selectedNumbers);
    //     $totalAmount = $validated['bet_amount'] * $numberOfSelectedNumbers;

    //     // Create the game record in the database
    //     $game = Game::create([
    //         'bet_amount' => $validated['bet_amount'],
    //         'total_players' => $numberOfSelectedNumbers,
    //         'total_calls' => 0,
    //         'status' => 'pending',
    //         'total_bet_amount' => $totalAmount,
    //         'profit' => 0,
    //     ]);

    //     // Optionally, associate selected cards with the game
    //     // ...

    //     return redirect()->route('branch.show-game-page');
    // }

    public function createGame(Request $request)
    {

        $games = Game::all();

        $selectedNumbers = explode(',', $request->input('selected_numbers'));

        $branch_user = User::where('role', 'branch', Auth::user()->id)->first();
        // dd($branch_user);
        // $branch_user = Branch::
        // $cutOffPercent = $branch_user->cut_off_percent ?? 0;
        $user = Auth::user();
        $cutOffPercent = $user->cut_off_percent ?? 0;
        $totalBetAmount = $request->bet_amount * count($selectedNumbers);

        $profit = ($cutOffPercent / 100) * $totalBetAmount;

        $game = Game::create([
            'branch_user_id' => $branch_user->id,
            'bet_amount' => $request->bet_amount,
            'total_players' => count($selectedNumbers),
            'total_calls' => 0,
            'status' => 'pending',
            'total_bet_amount' => $totalBetAmount,
            'profit' => $profit,
        ]);

        // Associate selected cards with the game
        // $game->bingoCards()->attach($request->input('selected_numbers'));

        return view('branch.game-page', ['game' => $game->id]);
    }



    public function gamePage($gameId)
    {
        $game = Game::with(['bingoCards', 'calledNumbers'])->findOrFail($gameId);
        $previousCall = $game->calledNumbers->last();

        return view('branch.game-page', [
            'game' => $game,
            'previousCall' => $previousCall
        ]);
    }

    // public function showGamePage()
    // {
    //     return view('branch.game-page');
    // }
    // public function gamePage(Request $request)
    // {
    //     $game = Game::findOrFail($request->game);

    //     return view('branch.game-page', compact('game'));
    // }

    public function cards()
    {
        $bingoCards = BingoCard::all();

        return view('branch.cards', compact('bingoCards'));
    }
}
