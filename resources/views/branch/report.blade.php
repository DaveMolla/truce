<!-- resources/views/branch/report.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Report</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-500 text-white">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Truce Bingo</h1>
            <nav>
                <ul>
                    <li class="mb-4">
                        <h2 class="text-3xl font-bold mb-6">{{ $branchUser->name }}</h2>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('branch.dashboard') }}"
                            class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('branch.cards') }}"
                            class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Boards</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('branch.report') }}"
                            class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Report</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('branch.history') }}"
                            class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Transaction History</a>
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
                <h2 class="text-4xl font-bold text-blue-500">Branch Report</h2>
            </div>

            <form action="{{ route('branch.report') }}" method="GET" class="text-center mb-8">
                <label for="start_date" class="text-lg mr-2">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                    class="p-2 bg-gray-800 text-white rounded">
                <label for="end_date" class="text-lg ml-4 mr-2">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                    class="p-2 bg-gray-800 text-white rounded">
                <button type="submit"
                    class="ml-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">View
                    Report</button>
            </form>

            <h3 class="text-2xl font-bold text-blue-400 mb-4">Report from {{ $startDate }} to {{ $endDate }}
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-800 rounded-lg">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="py-2 px-4">Date</th>
                            <th class="py-2 px-4">Total Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($games as $game)
                            <tr class="border-b border-gray-700">
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($game->game_date)->setTimezone('Africa/Addis_Ababa')->format('l, F j, Y g:i A')
                                }}
                                </td>
                                <td class="py-2 px-4">{{ number_format($game->total_profit, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-700">
                            <td class="py-2 px-4 text-right font-bold">Total Profit</td>
                            <td class="py-2 px-4 font-bold">{{ number_format($totalProfit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

            <div class="mt-4 flex justify-center">
                {{ $games->links() }}
            </div>
            </div>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>

</html>
