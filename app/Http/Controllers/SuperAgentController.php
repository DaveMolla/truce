<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\SuperAgent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperAgentController extends Controller
{
    public function index()
    {
        return view('super-agent.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->role === 'super_agent') {
                Auth::login($user);
                return redirect()->intended(route('super-agent.dashboard'));
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

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the super_agent dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $super_agent = SuperAgent::where('user_id', Auth::user()->id)->first();
        $agents = Agent::where('super_agent_id', $super_agent->id)->orderBy('created_at', 'desc')->paginate(10);

        return view('super-agent.dashboard', compact('agents'));
    }

    public function register()
    {
        return view('super-agent.register');
    }

    public function store(Request $request)
    {
        // Validation and logic to store the super_agent
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|unique:users,phone',
            'address' => 'required',
            'password' => 'required|min:6',
        ]);

        // Create the super_agent
        $superAgentUser = User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'] ?? 'super_agent',
        ]);

        $super_agent = SuperAgent::create([
            'user_id' => $superAgentUser->id,
        ]);

        return redirect()->route('admin.manage-super-agent-account')->with('success', 'Super Agent registered successfully!');
    }

    // public function branches()
    // {
    //     $branches = Branch::all();
    //     return view('super_agent.branches', compact('branches'));
    // }

    public function topUpAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $super_agent = Auth::user();
        $agent = Agent::find($request->agent_id);
        $agentUser = User::find($agent->user_id);

        if ($super_agent->current_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }

        $super_agent->current_balance -= $request->amount;
        $agentUser->current_balance += $request->amount;

        $super_agent->save();
        $agentUser->save();

        return redirect()->back()->with('success', 'Top-up successful');
    }

    public function withdrawAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $super_agent = Auth::user();
        $agent = Agent::find($request->agent_id);
        $agentUser = User::find($agent->user_id);

        if ($agentUser->current_balance < $request->amount) {
            return redirect()->back()->with('error', 'Agent has insufficient balance');
        }

        $agentUser->current_balance -= $request->amount;
        $super_agent->current_balance += $request->amount;

        $agentUser->save();
        $super_agent->save();

        return redirect()->back()->with('success', 'Withdraw successful');
    }
}
