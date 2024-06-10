<?php

// app/Http/Controllers/BranchController.php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGameRequest;
use App\Models\BingoCard;
use App\Models\Branch;
use App\Models\Game;
use App\Models\WinningPattern;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Session;


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
            'cut_off_percent' => '20',
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
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $user = User::where('phone', $request->phone)->first();

    //     if ($user && Hash::check($request->password, $user->password)) {
    //         if ($user->role === 'branch') {
    //             Auth::login($user);
    //             return redirect()->intended(route('branch.dashboard'));
    //         } else {
    //             return back()->withErrors([
    //                 'phone' => 'You do not have permission to access this area.',
    //             ]);
    //         }
    //     }

    //     return back()->withErrors([
    //         'phone' => 'The provided credentials do not match our records.',
    //     ]);
    // }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'agent':
                    return redirect()->route('agent.dashboard');
                case 'branch':
                    return redirect()->route('branch.dashboard');
                default:
                    Auth::logout();
                    return back()->withErrors(['phone' => 'Invalid role.']);
            }
        }

        return back()->withErrors(['phone' => 'The provided credentials do not match our records.']);
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
        $user = Auth::user();
        if ($user->role === 'branch') {
            $bingoCards = BingoCard::all();
            $winningPatterns = WinningPattern::all();
            $selectedCardIds = session('selected_card_ids', []);  // Retrieve from session

            return view('branch.dashboard', compact('bingoCards', 'winningPatterns', 'selectedCardIds'));
        }
        return redirect()->back();
    }
    public function history(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'branch') {
            // $recentGames = Game::latest()->paginate(10);
            $branchUser = Auth::user();
            // $games = Game::where('branch_user_id', $branchUser->id)->first();

            // $branch_user = Branch::where('user_id', Auth::user()->id)->first();
            // $branches = Branch::where('agent_id', $agent->id)->get();
            // dd($games);
            $recentGames = Game::where('branch_user_id', $branchUser->id)->latest()->paginate(10);

            $totalGames = Game::where('branch_user_id', $branchUser->id)->count();
            // dd($branchUser->id);

            $branch = Branch::where('user_id', $branchUser->id)->first();

            $walletBalance = $branchUser->current_balance ? $branchUser->current_balance : 0;

            return view('branch.history', compact('totalGames', 'walletBalance', 'branchUser', 'recentGames'));
        }
        return redirect()->back();

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

    // public function createGame(Request $request)
    // {

    //     $games = Game::all();

    //     $selectedNumbers = explode(',', $request->input('selected_numbers'


    //     $branch_user = User::where('role', 'branch', Auth::user()->id)->first();
    //     // dd($branch_user);
    //     // $branch_user = Branch::
    //     // $cutOffPercent = $branch_user->cut_off_percent ?? 0;
    //     $user = Auth::user();
    //     $cutOffPercent = $user->cut_off_percent ?? 0;
    //     $totalBetAmount = $request->bet_amount * count($selectedNumbers);

    //     $profit = ($cutOffPercent / 100) * $totalBetAmount;

    //     $game = Game::create([
    //         'branch_user_id' => $branch_user->id,
    //         'bet_amount' => $request->bet_amount,
    //         'total_players' => count($selectedNumbers),
    //         'total_calls' => 0,
    //         'status' => 'pending',
    //         'total_bet_amount' => $totalBetAmount,
    //         'profit' => $profit,
    //     ]);

    //     // Associate selected cards with the game
    //     // $game->bingoCards()->attach($request->input('selected_numbers'


    //     return view('branch.game-page', ['game' => $game->id]);
    // }
    public function createGame(CreateGameRequest $request)
    {
        $user = Auth::user();
        if ($user->role === 'branch') {

            $selectedNumbers = explode(',', $request->input('selected_numbers'));

            // $branch_user = User::where('role', 'branch', Auth::user()->id)->first();
            $user = Auth::user();
            $cutOffPercent = $user->cut_off_percent ?? 0;
            $totalBetAmount = $request->bet_amount * count($selectedNumbers);

            $profit = ($cutOffPercent / 100) * $totalBetAmount;
            // dd($profit);
            $user_balance = $user->current_balance;

            if ($user->current_balance <= $profit) {
                return back()->withErrors([
                    'phone' => 'You do not have enough balance.',
                ]);
            }


            // dd($request->all());
            $game = Game::create([
                'branch_user_id' => $user->id,
                'bet_amount' => $request->bet_amount,
            // dd($user),
                'total_players' => count($selectedNumbers),
                'total_calls' => 0,
                'status' => 'pending',
                'total_bet_amount' => $totalBetAmount,
                'profit' => $profit,
            // dd($user)

            ]);

            $user->update([
                'current_balance' => $user_balance - $profit,
            ]);
            $selectedNumbers = explode(',', $request->input('selected_numbers'));
            session(['selected_numbers' => $selectedNumbers]);
            session([
                'gameId' => $game->id,
                'game_setup' => [
                    'bet_amount' => $request->bet_amount,
                    'winning_pattern' => $request->winning_pattern,
                    'call_speed' => $request->call_speed,
                    'caller_language' => $request->caller_language,
                ]
            ]);
            Session::forget('callHistory');
            // dd($selectedNumbers);

            // Store game data in session
            Session::put('gameId', $game->id);
            Session::put('winning_pattern', $request->input('winning_pattern') ?? []);
            Session::put('selected_numbers', $request->input('selected_numbers') ?? []);
            $callSpeedMapping = [
                'very_fast' => 3000,
                'fast' => 5000,
            ];
            $callSpeed = $callSpeedMapping[$request->input('call_speed')] ?? 5000;
            Session::put('caller_speed', $callSpeed);

            return redirect()->route('bingo.index');
        }
        return redirect()->back();
    }


    // public function gamePage($gameId)
    // {
    //     $user = Auth::user();
    //     if ($user->role === 'branch') {

    //         $game = Game::with(['bingoCards', 'calledNumbers'])->findOrFail($gameId);
    //         $previousCall = $game->calledNumbers->last();

    //         return view('branch.game-page', [
    //             'game' => $game,
    //             'previousCall' => $previousCall
    //         ]);
    //     }
    //     return redirect()->back();
    // }

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
        $branchUser = Auth::user();

        return view('branch.cards', compact('bingoCards', 'branchUser'));
    }

    public function showReport(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'branch') {
            $branchUser = Auth::user();

            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
            $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

            $games = Game::select(
                DB::raw('date(created_at) as game_date,
                     sum(profit) as total_profit')
            )
                ->where('branch_user_id', $branchUser->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('date(created_at)'))
                ->orderBy('game_date', 'desc')
                ->paginate(10);

            $totalProfit = $games->sum('total_profit');

            return view('branch.report', compact('games', 'totalProfit', 'startDate', 'endDate', 'branchUser'));
        }
        return redirect()->back();
    }
}
