<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
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

        .form-control {
            background-color: #374151;
        }
    </style>
</head>

<body class="bg-gray-800 text-white">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-700 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Nexus Bingo</h1>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-lg hover:text-gray-400">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-super-agent-account') }}" class="text-lg hover:text-gray-400">Manage
                            Super Agent Account</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-agent-account') }}" class="text-lg hover:text-gray-400">Manage
                            Agent Account</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-branch-account') }}" class="text-lg hover:text-gray-400">Manage
                            Branch Account</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('bingo-cards.import-form') }}" class="text-lg hover:text-gray-400">Import
                            Bingo Cards</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.cards') }}" class="text-lg hover:text-gray-400">Boards</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Dashboard</h2>
                <div class="bg-blue-500 p-3 rounded-full">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white">Logout</button>
                    </form>
                </div>
            </div>

            {{-- <ul class="list-disc pl-5">
                <li class="mb-2">List Of Branches that has less than 10k in the wallet.</li>
                <li class="mb-2">List of Branches that haven’t been active for the past 7 days.</li>
                <li class="mb-2">Overall profits that the branches make within the 24 hrs.</li>
                <li>Etc...</li>
            </ul> --}}
            <h2 class="text-center">Welocme to admin dashboard</h2>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="text-right">
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
                <form action="{{ route('admin.dashboard') }}" method="GET" class="form-inline">
                    <label for="selected_date" class="form-label">Select Date:</label>
                    <input type="date" id="selected_date" name="selected_date"
                        value="{{ request('selected_date', today()->toDateString()) }}" class="form-control">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Check</button>
                </form>

            </div>

            <!-- Date Range Report -->



            <table class="min-w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Branch Number</th>
                        <th class="px-4 py-2">Agent Assigned</th>
                        <th class="px-4 py-2">Total Games Played</th>
                        <th class="px-4 py-2">Current Balance</th>
                        <th class="px-4 py-2">Total Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $row = 1;
                    @endphp
                    @foreach ($branches as $branch)
                        <tr>
                            <td class="border px-4 py-2">{{ $row++ }}</td>
                            <td class="border px-4 py-2">{{ $date ?: "$startDate to $endDate" }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->phone }}</td>
                            <td class="border px-4 py-2">{{ $branch->agent->user->name ?? 'No Agent' }}</td>
                            <td class="border px-4 py-2">{{ $branch->totalGames }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->current_balance ?? '0' }}</td>
                            <td class="border px-4 py-2">{{ $branch->totalProfit }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border px-4 py-2" colspan="6"><strong>Total</strong></td>
                        <td class="border px-4 py-2"><strong>{{ $totalProfitSum }}</strong></td>
                    </tr>
                </tbody>
            </table>

            {{-- <div class="mt-4">
                {{ $branches->links() }}
            </div> --}}

        </div>
    </div>

    @vite('resources/js/app.js')
</body>

</html>
