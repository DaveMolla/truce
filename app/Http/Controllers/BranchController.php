<?php

// app/Http/Controllers/BranchController.php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGameRequest;
use App\Models\BingoCard;
use App\Models\Branch;
use App\Models\Game;
use App\Models\User;
use App\Models\WinningPattern;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
                    $route = 'admin.dashboard';
                    break;
                case 'agent':
                    $route = 'agent.dashboard';
                    break;
                case 'branch':
                    $route = 'branch.dashboard';
                    break;
                case 'super_agent':
                    $route = 'super-agent.dashboard';
                    break;
                default:
                    Auth::logout();
                    $route = 'default';
                    break;
            }

            if ($route == 'default') {
                return redirect()->back()->withErrors(['phone' => 'The provided credentials do not match our records.']);
            }

            return redirect()->route($route);
        }

        return back()->withErrors(['phone' => 'The provided credentials do not match our records.']);
    }

    /**
     * Log the branch out of the application.
     *
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
            $selectedNumbers = explode(',', $request->input('selected_numbers', ''));
            session(['selected_numbers' => $selectedNumbers]);
            session([
                'gameId' => $game->id,
                'game_setup' => [
                    'bet_amount' => $request->bet_amount,
                    'winning_pattern' => $request->winning_pattern,
                    'call_speed' => $request->call_speed,
                    'caller_language' => $request->caller_language,
                ],
            ]);
            session()->forget('callHistory');
            // dd($selectedNumbers);

            // Store game data in session
            session()->put('gameId', $game->id);
            session()->put('winning_pattern', $request->input('winning_pattern') ?? []);
            session()->put('selected_numbers', $request->input('selected_numbers') ?? []);
            $callSpeedMapping = [
                'very_fast' => 3000,
                'fast' => 5000,
            ];
            $callSpeed = $callSpeedMapping[$request->input('call_speed')] ?? 5000;
            session()->put('caller_speed', $callSpeed);

            return redirect()->route('bingo.index');
        }

        return redirect()->back();
    }


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
