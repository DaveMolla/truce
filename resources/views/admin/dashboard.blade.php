<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
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
                        <a href="{{ route('admin.manage-agent-account') }}" class="text-lg hover:text-gray-400">Manage Agent Account</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-branch-account') }}" class="text-lg hover:text-gray-400">Manage Branch Account</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('bingo-cards.import-form') }}" class="text-lg hover:text-gray-400">Import Bingo Cards</a>
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
        </div>
    </div>

    @vite('resources/js/app.js')
</body>
</html>
