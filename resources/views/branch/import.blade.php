<!-- resources/views/branch/import.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Cards | NexusBingo</title>
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
                        <a href="{{ route('admin.dashboard') }}" class="text-lg hover:text-gray-400">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-super-agent-account') }}" class="text-lg hover:text-gray-400">Manage
                            Super Agent Account</a>
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
        <div class="flex-1 px-10">
                <div class="flex min-h-screen items-center justify-center">
                    <div class="bg-gray-800 p-10 rounded-lg">
                        <h1 class="text-3xl font-bold mb-6">Import Bingo Cards</h1>
                        <form action="{{ route('bingo-cards.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-white">Excel File</label>
                                <input type="file" id="file" name="file" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                            </div>
                            <button type="submit" class="bg-yellow-500 w-full text-gray-900 font-bold py-2 px-4 rounded">Upload</button>
                        </form>
                    </div>
                </div>
        </div>
    </div>

    @vite('resources/js/app.js')
</body>

</html>
