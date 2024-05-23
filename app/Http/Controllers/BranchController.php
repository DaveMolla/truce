<?php

// app/Http/Controllers/BranchController.php

namespace App\Http\Controllers;

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
        $winningPatterns = WinningPattern::all();

        return view('branch.dashboard', compact('winningPatterns'));
    }
    public function history()
    {
        // $recentGames = Game::latest()->paginate(10);

        return view('branch.history');
    }

    public function createGame(Request $request)
    {
        $request->validate([
            'bet_amount' => 'required|integer|min:1',
            'number_of_selected_numbers' => 'required|integer|min:1',
            'selected_numbers' => 'required|string',
            'winning_pattern' => 'required|exists:winning_patterns,id',
        ]);

        $totalBetAmount = $request->bet_amount * $request->number_of_selected_numbers;

        Game::create([
            'bet_amount' => $request->bet_amount,
            'total_players' => $request->number_of_selected_numbers,
            'total_calls' => 0, // Assuming this starts at 0
            'status' => 'pending',
            'total_bet_amount' => $totalBetAmount,
            'profit' => 0, // Assuming profit calculation is done elsewhere
        ]);

        return redirect()->route('branch.game-page')->with('success', 'Game created successfully!');
    }

    public function showGamePage()
    {
        return view('branch.game-page');
    }
}
