<?php

// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\BingoCard;
use App\Models\Branch;
use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.login');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users',
            'role' => 'required|string|in:admin,agent,branch',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => $request->role,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'agent') {
            Agent::create([
                'user_id' => $user->id,
            ]);
        }

        if ($request->role === 'branch') {
            Branch::create([
                'user_id' => $user->id,
                'agent_id' => $request->agent_id ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'User created successfully!');
    }

    /**
     * Handle an admin login request.
     *
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
            if ($user->role === 'admin') {
                Auth::login($user);

                return redirect()->intended(route('admin.dashboard'));
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
     * Log the admin out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect()->back();
        }

        $date = $request->input('selected_date');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $branches = Branch::with('agent')->paginate(10);
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

        return view('admin.dashboard', compact('branches', 'date', 'totalProfitSum', 'startDate', 'endDate'));
    }


    public function manageAgentAccount()
    {
        $agents = User::where('role', 'agent')->paginate(10);
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.manage-agent-account', compact('agents'));
        }

        return redirect()->back();
    }

    public function manageBranchAccount()
    {
        $agents = Agent::all();
        $branches = Branch::with('agent')->paginate(10);
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.manage-branch-account', compact('branches', 'agents'));
        }

        return redirect()->back();
    }

    public function topUpAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        if ($user->role === 'admin') {
            $agent = User::find($request->agent_id);
            $agent->current_balance = ($agent->current_balance ?? 0) + $request->amount;
            $agent->save();

            return redirect()->back()->with('success', 'Amount topped up successfully!');
        }

        return redirect()->back();
    }

    public function withdrawAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        if ($user->role === 'admin') {
            $agent = User::find($request->agent_id);
            $agent->current_balance = max(($agent->current_balance ?? 0) - $request->amount, 0);
            $agent->save();

            return redirect()->back()->with('success', 'Amount withdrawn successfully!');
        }

        return redirect()->back();
    }

    public function topUpBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);
        // dd($request->amount);

        $user = Auth::user();
        if ($user->role === 'admin') {
            $branch = Branch::find($request->branch_id);
            $branchUser = User::find($branch->user_id);
            // $branch = User::find($request->branch_id);
            // dd($branchUser);
            $branchUser->current_balance += $request->amount;

            // $branch->current_balance = ($branch->current_balance ?? 0) + $request->amount;
            // $branch->save();
            $branchUser->save();

            return redirect()->back()->with('success', 'Amount topped up successfully!');
        }

        return redirect()->back();
    }

    public function withdrawBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);
        $user = Auth::user();
        if ($user->role === 'admin') {

            $branch = Branch::find($request->branch_id);

            $branchUser = User::find($branch->user_id);
            $branchUser->current_balance -= $request->amount;

            // $branch->current_balance = max(($branch->current_balance ?? 0) - $request->amount, 0);
            $branchUser->save();

            return redirect()->back()->with('success', 'Amount withdrawn successfully!');
        }

        return redirect()->back();
    }

    public function assignBranch(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'branch_id' => 'required',
            'agent_id' => 'required',
        ]);

        $user = Auth::user();
        if ($user->role === 'admin') {
            $branch = Branch::find($request->branch_id);

            $branch->update([
                'agent_id' => $request->agent_id,
            ]);

            return redirect()->back()->with('success', 'Branch assigned to agent successfully!');
        }

        return redirect()->back();
    }

    public function updateAgentPassword(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'newPassword' => 'required|string',
        ]);

        $user = Auth::user();
        // dd($request->agent_id);
        if ($user->role === 'admin') {
            $user = User::findOrFail($request->agent_id);
            $user->password = Hash::make($request->newPassword);
            $user->save();

            return redirect()->back()->with('success', 'Password changed successfully!');
        }

        return redirect()->back();
    }

    public function updateBranchPassword(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:users,id',
            'newPassword' => 'required|string',
        ]);

        $user = Auth::user();
        // dd($request->branch_id);
        if ($user->role == 'admin') {
            $branch = Branch::with('user')->find($request->branch_id);
            $branch->user->update([
                'password' => bcrypt($request->newPassword),
            ]);

            return redirect()->back()->with('success', 'Password changed successfully!');
        }

        return redirect()->back();
    }

    public function setCutoff(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:users,id',
            'cutoffPercent' => 'required|numeric|min:0|max:100',
        ]);

        $user = Auth::user();
        if ($user->role === 'admin') {
            $branch = Branch::findOrFail($request->branch_id);

            $branchUser = User::find($branch->user_id);
            $branchUser->update([
                'cut_off_percent' => $request->cutoffPercent,
            ]);

            // dd($request->cut_off_percent);
            return redirect()->back()->with('success', 'Cutoff percent set successfully!');
        }

        return redirect()->back();
    }

    public function cards()
    {
        $bingoCards = BingoCard::all();

        return view('admin.cards', compact('bingoCards'));
    }
}
