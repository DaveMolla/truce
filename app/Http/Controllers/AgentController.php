<?php

// app/Http/Controllers/AgentController.php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Branch;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AgentController extends Controller
{
    /**
     * Show the agent login form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('agent.login');
    }

    /**
     * Handle an agent login request.
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
            if ($user->role === 'agent') {
                Auth::login($user);
                return redirect()->intended(route('agent.dashboard'));
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
     * Log the agent out of the application.
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
     * Show the agent dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $agent = Agent::where('user_id', Auth::user()->id)->first();
        $branches = Branch::where('agent_id', $agent->id)->orderBy('created_at', 'desc')->paginate(10);

        return view('agent.dashboard', compact('branches'));
    }

    public function register()
    {
        return view('agent.register');
    }

    public function store(Request $request)
    {
        // Validation and logic to store the agent
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|unique:users,phone',
            'address' => 'required',
            'password' => 'required|min:6',
        ]);

        // Create the agent
        $agentUser = User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'] ?? 'agent',
        ]);

        $agent = Agent::create([
            'user_id' => $agentUser->id,
        ]);

        return redirect()->route('admin.manage-agent-account')->with('success', 'Agent registered successfully!');
    }

    // public function branches()
    // {
    //     $branches = Branch::all();
    //     return view('agent.branches', compact('branches'));
    // }

    public function branchesReport(Request $request)
    {
        $agent = Agent::where('user_id', Auth::user()->id)->first();

        $date = $request->input('selected_date');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $branches = Branch::where('agent_id', $agent->id)
            ->with([
                'agent',
                'game' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->orderBy(DB::raw('(SELECT MAX(created_at) FROM games WHERE games.branch_user_id = branches.user_id)'), 'desc')->get();
        $totalProfitSum = 0;
        foreach ($branches as $branch) {
            $query = Game::where('branch_user_id', $branch->user->id);

            if ($date) {
                $query->whereDate('created_at', $date);
            } else {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $branch->totalGames = $query->count();
            $branch->totalProfit = $query->sum('profit');
            $totalProfitSum += $branch->totalProfit;
        }
        return view('agent.branches-report', compact('branches', 'date', 'totalProfitSum', 'startDate', 'endDate', 'agent'));
    }

    public function topUpBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $agent = Auth::user();
        $branch = Branch::find($request->branch_id);
        $branchUser = User::find($branch->user_id);

        if ($agent->current_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }

        $agent->current_balance -= $request->amount;
        $branchUser->current_balance += $request->amount;

        $agent->save();
        $branchUser->save();

        return redirect()->back()->with('success', 'Top-up successful');
    }

    public function withdrawBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $agent = Auth::user();
        $branch = Branch::find($request->branch_id);
        $branchUser = User::find($branch->user_id);

        if ($branchUser->current_balance < $request->amount) {
            return redirect()->back()->with('error', 'Branch has insufficient balance');
        }

        $branchUser->current_balance -= $request->amount;
        $agent->current_balance += $request->amount;

        $branchUser->save();
        $agent->save();

        return redirect()->back()->with('success', 'Withdraw successful');
    }
}
