<!-- resources/views/branch/game-page.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Page | NexusBingo</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-800 text-white">
    <div class="min-h-screen flex items-center justify-center">
        <h1 class="text-3xl font-bold">Welcome to the Game Page</h1>
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mt-4">
                {{ session('success') }}
            </div>
        @endif
    </div>
    @vite('resources/js/app.js')
</body>

</html>
