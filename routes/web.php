<?php

// routes/web.php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('branch.login');
});

// Admin routes
Route::get('admin/login', [AdminController::class, 'index'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login']);
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/manage-agent-account', [AdminController::class, 'manageAgentAccount'])->name('admin.manage-agent-account');
    Route::get('admin/manage-branch-account', [AdminController::class, 'manageBranchAccount'])->name('admin.manage-branch-account');
    Route::post('admin/top-up-agent', [AdminController::class, 'topUpAgent'])->name('admin.top-up-agent');
    Route::post('admin/withdraw-agent', [AdminController::class, 'withdrawAgent'])->name('admin.withdraw-agent');
    Route::post('admin/top-up-branch', [AdminController::class, 'topUpBranch'])->name('admin.top-up-branch');
    Route::post('admin/withdraw-branch', [AdminController::class, 'withdrawBranch'])->name('admin.withdraw-branch');
    Route::post('admin/assign-branch', [AdminController::class, 'assignBranch'])->name('admin.assign-branch');
});

// Agent routes
Route::get('agent/login', [AgentController::class, 'index'])->name('agent.login');
Route::post('agent/login', [AgentController::class, 'login']);
Route::post('agent/logout', [AgentController::class, 'logout'])->name('agent.logout');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('agent/dashboard', [AgentController::class, 'dashboard'])->name('agent.dashboard');
    Route::get('/agent/branches', [AgentController::class, 'branches'])->name('agent.branches');
    Route::post('/agent/branch/top-up', [AgentController::class, 'topUpBranch'])->name('agent.top-up-branch');
    Route::post('/agent/branch/withdraw', [AgentController::class, 'withdrawBranch'])->name('agent.withdraw-branch');
});

// Branch routes
Route::get('branch/login', [BranchController::class, 'index'])->name('branch.login');
Route::post('branch/login', [BranchController::class, 'login']);
Route::post('branch/logout', [BranchController::class, 'logout'])->name('branch.logout');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('branch/dashboard', [BranchController::class, 'dashboard'])->name('branch.dashboard');
    Route::get('branch/history', [BranchController::class, 'history'])->name('branch.history');
    Route::post('branch/create-game', [BranchController::class, 'createGame'])->name('branch.create-game');
    Route::get('branch/game-page', [BranchController::class, 'showGamePage'])->name('branch.game-page');

});
