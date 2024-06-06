<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Dashboard | TruceBingo</title>
    @vite('resources/css/app.css')
    <style>
        .number-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 0.1rem;
        }

        .number-grid button {
            background-color: #333;
            color: white;
            font-size: 1.5rem;
            border: 1px solid #444;
            border-radius: 0.375rem;
            padding: 0.5rem;
            transition: background-color 0.3s;
            margin: 2px;
        }

        .number-grid button.selected {
            background-color: #019DD6;
        }

        .pattern-display {
            background-color: #fff;
            width: 220px;
            border-collapse: collapse;
            margin-right: 200px;
            margin-top: -500px;
        }

        .pattern-grid table {
            border-collapse: collapse;
            width: 100%;
        }

        .pattern-grid th,
        .pattern-grid td {
            border: 3px solid #014576;
            width: 2rem;
            height: 2rem;
            text-align: center;
            padding: 5px;
        }

        .pattern-grid th {
            background-color: #014576;
            color: white;
        }

        .pattern-grid .circle {
            width: 1.7rem;
            height: 1.7rem;
            background-color: #019DD6;
            border-radius: 50%;
            margin: auto;
        }

        .pattern-grid .free-spot {
            background-color: #ffd700;
            width: 1.7rem;
            height: 1.7rem;
            border-radius: 50%;
            margin: auto;
        }
         .pattern-grid th:nth-child(2) {
            background-color: #014576;
        }

        .pattern-grid th:nth-child(3) {
            background-color: #014576;
        }

        .pattern-grid th:nth-child(4) {
            background-color: #014576;
        }

        .pattern-grid th:nth-child(5) {
            background-color: #014576;
        }
        .caller_language:hover
        {
            background-color: #014576;
        }
    </style>
</head>

<body class="bg-gray-800">

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-gray-500 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Truce Bingo</h1>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('branch.history') }}"
                            class="block px-4 py-2 text-sm text-black hover:bg-gray-400">Transaction History</a>
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
        <div class="p-10 w-full max-w-6xl mx-auto">
            <div class="grid grid-cols-12 gap-4">

                {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
                <!-- First column (8 columns) -->
                <div class="col-span-12 md:col-span-8">
                    <form method="POST" action="{{ route('branch.game-page') }}">
                        @csrf

                        @if ($errors->any())
                        <div class="text-red-500 alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="bet_amount" class="block mb-2 text-sm font-medium text-white">Bet
                                    amount</label>
                                <input type="text" id="bet_amount" name="bet_amount"
                                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5 placeholder-gray-400"
                                    placeholder="Enter bet amount">
                            </div>

                            <div>
                                <label for="winning_pattern" class="block mb-2 text-sm font-medium text-white">Winning
                                    pattern</label>
                                <select id="winning_pattern" name="winning_pattern"
                                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                                    @foreach ($winningPatterns as $pattern)
                                        <option value="{{ $pattern->id }}" data-pattern="{{ $pattern->pattern_data }}">
                                            {{ $pattern->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="call_speed" class="block mb-2 text-sm font-medium text-white">Call
                                    speed</label>
                                <select id="call_speed" name="call_speed"
                                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                                    <option value="very_fast">Very Fast (3s)</option>
                                    <option value="fast">Fast (5s)</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>

                            <div>
                                <label for="caller_language" class="block mb-2 text-sm font-medium text-white">Caller
                                    language</label>
                                <select id="caller_language" name="caller_language"
                                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                                    <option value="english_female_very_fast">English Female Very Fast</option>
                                    <option value="english_female_fast">English Female Fast</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>

                        <div class="number-grid mb-5">
                            @foreach ($bingoCards as $card)
                                <button type="button" class="number-button" data-card-id="{{ $card->id }}">
                                    {{ $card->id }}
                                </button>
                            @endforeach
                        </div>

                        <input type="hidden" name="selected_numbers" id="selected_numbers">
                        <input type="hidden" name="number_of_selected_numbers" id="number_of_selected_numbers">
                        <input type="hidden" name="total_amount" id="total_amount">

                        <button type="submit" style="background-color: #019DD6;"
                            class="w-full text-gray-100 font-bold py-2 px-4 rounded">
                            Create Game
                        </button>
                    </form>
                </div>

                <!-- Second column (4 columns) -->

            </div>
        </div>
        <div class="pattern-display">
            <div class="pattern-grid" id="pattern_grid">
                <!-- The pattern grid will be generated here -->
            </div>
        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const numberButtons = document.querySelectorAll('.number-button');
            const betAmountInput = document.getElementById('bet_amount');
            const selectedNumbersInput = document.getElementById('selected_numbers');
            const numberOfSelectedNumbersInput = document.getElementById('number_of_selected_numbers');
            const winningPatternSelect = document.getElementById('winning_pattern');
            const totalAmountInput = document.getElementById('total_amount');
            const patternGrid = document.getElementById('pattern_grid');
            let selectedNumbers = [];

            function updateTotalAmount() {
                const betAmount = parseFloat(betAmountInput.value) || 0;
                const numberOfSelected = selectedNumbers.length;
                const totalAmount = betAmount * numberOfSelected;
                totalAmountInput.value = totalAmount; // Update the hidden input
            }

            numberButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cardId = this.dataset.cardId;
                    if (selectedNumbers.includes(cardId)) {
                        selectedNumbers = selectedNumbers.filter(n => n !== cardId);
                        this.classList.remove('selected');
                    } else {
                        selectedNumbers.push(cardId);
                        this.classList.add('selected');
                    }
                    selectedNumbersInput.value = selectedNumbers.join(',');
                    numberOfSelectedNumbersInput.value = selectedNumbers.length;
                    updateTotalAmount(); // Update total amount whenever selection changes
                });
            });

            betAmountInput.addEventListener('input', updateTotalAmount);

            winningPatternSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const patternData = JSON.parse(selectedOption.dataset.pattern);

                patternGrid.innerHTML = ''; // Clear existing grid

                const table = document.createElement('table');
                const headerRow = document.createElement('tr');
                const headers = ['B', 'I', 'N', 'G', 'O'];

                headers.forEach(header => {
                    const th = document.createElement('th');
                    th.textContent = header;
                    headerRow.appendChild(th);
                });
                table.appendChild(headerRow);

                patternData.forEach((row, rowIndex) => {
                    const tr = document.createElement('tr');
                    row.forEach((cell, cellIndex) => {
                        const td = document.createElement('td');
                        const div = document.createElement('div');
                        if (cell || (rowIndex === 2 && cellIndex === 2)) {
                            div.className = (rowIndex === 2 && cellIndex === 2) ? 'free-spot' : 'circle';
                        }
                        td.appendChild(div);
                        tr.appendChild(td);
                    });
                    table.appendChild(tr);
                });

                patternGrid.appendChild(table);
            });

            // Trigger change event to load the default pattern on page load
            winningPatternSelect.dispatchEvent(new Event('change'));
            updateTotalAmount(); // Update total amount on page load

        });
    </script>
    @vite('resources/js/app.js')
</body>

</html>
