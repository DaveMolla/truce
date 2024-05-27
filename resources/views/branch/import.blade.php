<!-- resources/views/branch/import.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Bingo Cards | TruceBingo</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-500 text-white">
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
    @vite('resources/js/app.js')
</body>
</html>
