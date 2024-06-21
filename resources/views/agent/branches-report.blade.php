<!-- resources/views/agent/branches.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch List | Nexus Agent</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #374151;
        }

        th,
        td {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }

        thead {
            background-color: #f3f3f3;
            color: #374151
        }

        /* .form-control {
            background-color: #374151;
        } */
    </style>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-900 text-white">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Nexus Bingo</h1>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('agent.dashboard') }}" class="text-lg hover:text-gray-400">Branches</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('agent.branches-report') }}" class="text-lg hover:text-gray-400">Branches Report</a>
                    </li>
                    {{-- <li class="mb-4">
                        <a href="{{ route('agent.branches') }}" class="text-lg hover:text-gray-400">Branches</a>
                    </li> --}}
                </ul>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Branches</h2>
                <div class="bg-blue-500 p-3 rounded-full">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Agent Balance -->
            <div class="bg-gray-800 p-4 rounded-lg mb-8">
                @if (session('success'))
                    <div class="bg-green-500 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-gray-800 text-red p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
            <h1 class="text-center text-xl font-bold">Branches Report for Agent - {{$agent->user->name}}</h1>

                <h3 class="text-xl font-bold">Agent Balance: {{ Auth::user()->current_balance ?? 0.0 }}</h3>
            </div>


            <form action="{{ route('agent.branches-report') }}" method="GET" class="text-right">
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                    class="p-2 bg-gray-800 text-white rounded">
                <label for="end_date" class="text-lg ml-4 mr-2">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                    class="p-2 bg-gray-800 text-white rounded">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Check</button>
            </form>
            <!-- Single Day Report -->
            <div style="margin-top: -35px" class="mb-8">
                <form action="{{ route('agent.branches-report') }}" method="GET" class="form-inline">
                    <label for="selected_date" class="form-label">Select Date:</label>
                    <input type="date" id="selected_date" name="selected_date"
                        value="{{ request('selected_date', today()->toDateString()) }}" class="form-control" style="background-color: #374151;">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Check</button>
                </form>

            </div>
            <!-- Table content here -->
            <table class="min-w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Branch Number</th>
                        <th class="px-4 py-2">Agent Assigned</th>
                        <th class="px-4 py-2">Total Games Played</th>
                        <th class="px-4 py-2">Current Balance</th>
                        <th class="px-4 py-2">Total Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($branches as $branch)
                        <tr>
                            <td class="border px-4 py-2">{{ $date ?: "$startDate to $endDate" }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->phone }}</td>
                            <td class="border px-4 py-2">{{ $branch->agent->user->name ?? 'No Agent' }}</td>
                            <td class="border px-4 py-2">{{ $branch->totalGames }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->current_balance ?? '0' }}</td>
                            <td class="border px-4 py-2">{{ $branch->totalProfit }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border px-4 py-2" colspan="5"><strong>Total</strong></td>
                        <td class="border px-4 py-2"><strong>{{ $totalProfitSum }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4">
                {{ $branches->links() }}
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
</body>

</html>
