<?php

// routes/web.php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BingoCardController;
use App\Http\Controllers\BingoController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\SuperAgentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('branch.login');
});

// Route::get('/', function () {
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


    Route::get('admin/manage-super-agent-account', [AdminController::class, 'manageSuperAgentAccount'])->name('admin.manage-sa-account');
    Route::post('admin/top-up-super-agent', [AdminController::class, 'topUpSuperAgent'])->name('admin.top-up-super-agent');
    Route::post('admin/withdraw-super-agent', [AdminController::class, 'withdrawSuperAgent'])->name('admin.withdraw-super-agent');
    Route::post('admin/update-super-agent-password', [AdminController::class, 'updateSuperAgentPassword'])->name('admin.update-super-agent-password');
    Route::post('admin/assign-agent', [AdminController::class, 'assignAgent'])->name('admin.assign-agent');
    // Route::post('super-agent/store', [SuperAgentController::class, 'register'])->name('super-agent.store');

    Route::post('admin/top-up-branch', [AdminController::class, 'topUpBranch'])->name('admin.top-up-branch');
    Route::post('admin/withdraw-branch', [AdminController::class, 'withdrawBranch'])->name('admin.withdraw-branch');
    Route::post('admin/assign-branch', [AdminController::class, 'assignBranch'])->name('admin.assign-branch');

    Route::get('bingo-cards/import', [BingoCardController::class, 'showImportForm'])->name('bingo-cards.import-form');
    Route::post('bingo-cards/import', [BingoCardController::class, 'import'])->name('bingo-cards.import');
    Route::get('agent/register', [AgentController::class, 'register'])->name('agent.register');
    Route::post('agent/store', [AgentController::class, 'store'])->name('agent.store');

    Route::get('branch/register', [BranchController::class, 'register'])->name('branch.register');
    Route::post('branch/store', [BranchController::class, 'store'])->name('branch.store');

    Route::get('super-agent/register', [SuperAgentController::class, 'register'])->name('super-agent.register');
    Route::post('super-agent/store', [SuperAgentController::class, 'store'])->name('super-agent.store');

    Route::post('admin/update-agent-password', [AdminController::class, 'updateAgentPassword'])->name('admin.update-agent-password');
    Route::post('admin/update-branch-password', [AdminController::class, 'updateBranchPassword'])->name('admin.update-branch-password');
    Route::post('admin/set-cutoff', [AdminController::class, 'setCutoff'])->name('admin.set-cutoff');

    Route::get('admin/boards', [AdminController::class, 'cards'])->name('admin.cards');

});

// Route::group(['prefix' => 'agent'], function () {

// });

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

// SuperAgent routes
Route::get('super-agent/login', [SuperAgentController::class, 'index'])->name('agent.login');
Route::post('super-agent/login', [SuperAgentController::class, 'login']);
Route::post('super-agent/logout', [SuperAgentController::class, 'logout'])->name('agent.logout');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('super-agent/dashboard', [SuperAgentController::class, 'dashboard'])->name('super-agent.dashboard');
    Route::get('/super-agent/agents', [SuperAgentController::class, 'agents'])->name('super-agent.agents');
    Route::post('/super-agent/agent/top-up', [SuperAgentController::class, 'topUpAgent'])->name('agent.top-up-agent');
    Route::post('/super-agent/agent/withdraw', [SuperAgentController::class, 'withdrawAgent'])->name('agent.withdraw-agent');
});

// Branch routes
Route::get('branch/login', [BranchController::class, 'index'])->name('branch.login');
Route::post('branch/login', [BranchController::class, 'login']);
Route::post('branch/logout', [BranchController::class, 'logout'])->name('branch.logout');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('branch/dashboard', [BranchController::class, 'dashboard'])->name('branch.dashboard');
    Route::get('branch/boards', [BranchController::class, 'cards'])->name('branch.cards');
    Route::get('branch/history', [BranchController::class, 'history'])->name('branch.history');
    Route::post('branch/game-page', [BranchController::class, 'createGame'])->name('branch.game-page');
    Route::get('branch/game/{game}', [BranchController::class, 'gamePage']);

    Route::get('branch/report', [BranchController::class, 'showReport'])->name('branch.report');
    Route::post('/bingo/check', [BingoController::class, 'checkCard'])->name('bingo.check');

    Route::get('bingo', [BingoController::class, 'index'])->name('bingo.index');
    Route::post('bingo/call', [BingoController::class, 'callNextNumber'])->name('bingo.call');
    Route::post('bingo/reset', [BingoController::class, 'resetBoard'])->name('bingo.reset');
    Route::post('bingo/end', [BingoController::class, 'endGame'])->name('bingo.end');

    // Route::post('fetch-card', [BingoController::class,'fetchCard'])->name('fetch.card');

});
