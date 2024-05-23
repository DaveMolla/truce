<!-- resources/views/branch/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Dashboard | NexusBingo</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-500 text-white">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="bg-gray-800 w-64 p-4">
        <h1 class="text-3xl font-bold mb-6">Nexus Bingo</h1>
        <nav>
            <ul>
                <li class="mb-4">
                    <button id="dashboardDropdown" class="text-lg hover:text-gray-400">Dashboard</button>
                    <div id="dashboardMenu" class="hidden mt-2 py-2 bg-gray-800 rounded shadow-lg">
                        <a href="{{ route('branch.dashboard') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Dashboard</a>
                        {{-- <a href="{{ route('branch.create-game') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Create Game</a> --}}
                    </div>
                </li>
                <li class="mb-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-lg hover:text-gray-400">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main content -->
    <div class="flex-1 p-10">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold">Dashboard</h2>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-gray-800 p-4 rounded-lg text-center">
                <h3 class="text-lg font-semibold">Total Games</h3>
                <p class="text-2xl mt-2">8133</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg text-center">
                <h3 class="text-lg font-semibold">Wallet</h3>
                <p class="text-2xl mt-2">-18.00</p>
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4">Recent Games</h3>
        <table class="min-w-full bg-gray-800 rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Bet amount</th>
                    <th class="px-4 py-2">Total Players</th>
                    <th class="px-4 py-2">Total Calls</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Total Bet amount</th>
                    <th class="px-4 py-2">Profit</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($recentGames as $game) --}}
                    <tr>
                        {{-- <td class="border px-4 py-2">{{ $game->created_at->format('l, F j, Y g:i A') }}</td>
                        <td class="border px-4 py-2">{{ $game->bet_amount }}</td>
                        <td class="border px-4 py-2">{{ $game->total_players }}</td>
                        <td class="border px-4 py-2">{{ $game->total_calls }}</td>
                        <td class="border px-4 py-2">{{ $game->status }}</td>
                        <td class="border px-4 py-2">{{ $game->total_bet_amount }}</td>
                        <td class="border px-4 py-2">{{ $game->profit }}</td> --}}
                    </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
        {{-- <div class="mt-4 flex justify-center">
            {{ $recentGames->links() }}
        </div> --}}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dashboardDropdown = document.getElementById('dashboardDropdown');
        const dashboardMenu = document.getElementById('dashboardMenu');

        dashboardDropdown.addEventListener('click', function () {
            dashboardMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function (event) {
            if (!dashboardDropdown.contains(event.target) && !dashboardMenu.contains(event.target)) {
                dashboardMenu.classList.add('hidden');
            }
        });
    });
</script>

@vite('resources/js/app.js')
</body>
</html>
