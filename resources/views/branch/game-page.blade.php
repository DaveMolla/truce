<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Page | Bingo</title>
    <!-- Ensure the path here is correct -->
    @vite('resources/css/app.css')
    <style>
        .pattern-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.1rem;
            margin-top: 1rem;
            background-color: #ffffff;
            padding: 0.5rem;
            border-radius: 0.375rem;
            margin-right: 100px;
            margin-top: -500px;
        }

        .pattern-grid .cell {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #444;
        }

        .pattern-grid .circle {
            width: 1.5rem;
            height: 1.5rem;
            background-color: #1d4ed8;
            border-radius: 50%;
        }

        .pattern-grid .free-spot {
            background-color: #ffd700;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-gray-800 text-white">

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div class="mb-3 text-gray-100 ">LETS PLAY BINGO <br>
        <div class="pattern-grid" id="pattern_grid">
            <!-- The pattern grid will be generated here -->
        </div></div>

    <div class="mb-3 text-gray-100 dark:text-gray-400">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="check_board" class="block mb-2 text-sm font-medium text-white">Check</label>
                <input type="text" id="check_board" name="check_board"
                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5 placeholder-gray-400"
                    placeholder="Enter bet amount">
            </div>

            <div>
                <label for="winning_pattern" class="block mb-2 text-sm font-medium text-white">Winning
                    pattern</label>
                <select id="winning_pattern" name="winning_pattern"
                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                    {{-- @foreach ($winningPatterns as $pattern)
                        <option value="{{ $pattern->id }}" data-pattern="{{ $pattern->pattern_data }}">
                            {{ $pattern->name }}</option>
                    @endforeach --}}
                </select>
            </div>
        </div>
    </div>
</div>
<button class="btn btn-danger">Primary Button</button>
<button class="btn btn-primary">Primary Button</button>
@vite(['resources/css/bootstrap.css', 'resources/js/app.js'])

</body>
</html>
