<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Cards | NexusBingo</title>
    @vite('resources/css/app.css')
    <style>
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .bingo-card {
            background-color: #333;
            padding: 1rem;
            border-radius: 0.5rem;
            display: grid;
            grid-template-rows: repeat(5, 1fr) auto;
            gap: 0.5rem;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
        }

        .cell {
            background-color: #444;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border-radius: 0.25rem;
            font-size: 1rem;
        }

        .free-spot {
            background-color: #facc15;
        }

        .card-id {
            text-align: center;
            margin-top: 0.5rem;
            font-weight: bold;
            color: #fff;
        }
    </style>
</head>

<body class="bg-gray-500 text-white">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Nexus Bingo</h1>
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
            <div class="card-grid mb-5">
                @foreach ($bingoCards as $card)
                    <div class="bingo-card" data-card-id="{{ $card->id }}">
                        @foreach (json_decode($card->card_data) as $row)
                            <div class="row">
                                @foreach ($row as $cell)
                                    <div class="cell {{ $cell == 'FREE' ? 'free-spot' : '' }}">{{ $cell }}</div>
                                @endforeach
                            </div>
                        @endforeach
                        <div class="card-id">Card : {{ $card->id }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dashboardDropdown = document.getElementById('dashboardDropdown');
            const dashboardMenu = document.getElementById('dashboardMenu');

            dashboardDropdown.addEventListener('click', function() {
                dashboardMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!dashboardDropdown.contains(event.target) && !dashboardMenu.contains(event.target)) {
                    dashboardMenu.classList.add('hidden');
                }
            });
        });
    </script>

    @vite('resources/js/app.js')
</body>

</html>
