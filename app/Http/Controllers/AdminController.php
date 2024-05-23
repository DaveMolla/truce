<?php

// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
     * @param  \Illuminate\Http\Request  $request
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
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function manageAgentAccount()
    {
        $agents = User::where('role', 'agent')->get();
        return view('admin.manage-agent-account', compact('agents'));
    }

    public function manageBranchAccount()
    {
        $agents = User::where('role', 'agent')->get();
        $branches = User::where('role', 'branch')->get();
        return view('admin.manage-branch-account', compact('branches', 'agents'));
    }

    public function topUpAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $agent = User::find($request->agent_id);
        $agent->current_balance = ($agent->current_balance ?? 0) + $request->amount;
        $agent->save();

        return redirect()->back()->with('success', 'Amount topped up successfully!');
    }

    public function withdrawAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $agent = User::find($request->agent_id);
        $agent->current_balance = max(($agent->current_balance ?? 0) - $request->amount, 0);
        $agent->save();

        return redirect()->back()->with('success', 'Amount withdrawn successfully!');
    }

    public function topUpBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $branch = User::find($request->branch_id);
        $branch->current_balance = ($branch->current_balance ?? 0) + $request->amount;
        $branch->save();

        return redirect()->back()->with('success', 'Amount topped up successfully!');
    }

    public function withdrawBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $branch = User::find($request->branch_id);
        $branch->current_balance = max(($branch->current_balance ?? 0) - $request->amount, 0);
        $branch->save();

        return redirect()->back()->with('success', 'Amount withdrawn successfully!');
    }

    public function assignBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'agent_id' => 'required|exists:agents,id',
        ]);

        $branch = Branch::find($request->branch_id);
        $branch->agent_id = $request->agent_id;
        $branch->save();

        return redirect()->back()->with('success', 'Branch assigned to agent successfully!');
    }

}
