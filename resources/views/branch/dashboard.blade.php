<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Dashboard | NexusBingo</title>
    @vite('resources/css/app.css')
    <style>
        .dropdown-toggle {
            cursor: pointer;
            margin-top: 25px;
            border-radius: 10%;

        }

        .relative {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 1000;
        }

        .number-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 0.1rem;
            height: 750px;
            width: 750px;
            margin-right: 15px;
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
            /* background-color: #fff; */
            width: 220px;
            border-collapse: collapse;
            margin-right: -600px;
            margin-top: 200px;
        }

        .pattern-grid table {
            border-collapse: collapse;
            width: 350px;
            height: 350px;
        }

        .pattern-grid th,
        .pattern-grid td {
            background-color: #fff;
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
            width: 2rem;
            height: 2rem;
            background-color: #019DD6;
            border-radius: 50%;
            margin: auto;
        }

        .pattern-grid .free-spot {
            background-color: #ffd700;
            width: 2rem;
            height: 2rem;
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

        .caller_language:hover {
            background-color: #014576;
        }

        @media (max-width: 600px) {
            .number-grid {
                grid-template-columns: repeat(5, 1fr);
                /* Reduce the number of columns on small screens */
                height: 80vw;
                width: 80vw;
            }

            .number-grid button {
                font-size: 2vw;
            }
        }
    </style>
</head>

<body class="bg-gray-800">
    <div class="min-h-screen flex items-center justify-center">
        {{-- <div class="bg-gray-500 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Nexus Bingo</h1>
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
        </div> --}}
        <div class="relative">
            <!-- Dropdown Button -->
            <button class="dropdown-toggle bg-gray-500 w-64 p-4 text-3xl font-bold mb-6">
                History <!-- You can change the button text or add an icon -->
            </button>


            <!-- Dropdown Menu -->
            <nav id="dropdownMenu" class="absolute right-0 mt-2 bg-gray-500 w-64 hidden">
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('branch.history') }}" class="text-lg hover:text-gray-400"
                            style="align-items: center">Transaction History</a>
                    </li>
                    <li class="mb-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-lg hover:text-gray-400"
                                style="align-items: center">Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Main content -->
        <div class="p-10 w-full max-w-6xl mx-auto flex">
            <!-- Number Grid (Bingo Cards) -->
            <div class="w-2/3">
                <div class="number-grid mb-5">
                    @foreach ($bingoCards as $card)
                        <button type="button" class="number-button" data-card-id="{{ $card->id }}">
                            {{ $card->id }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Input Fields and Pattern Display -->
            <div class="w-1/3 pl-10">
                <form method="POST" action="{{ route('branch.game-page') }}">
                    @csrf



                    <div class="grid gap-4 mb-4" style="margin-top: 100px; margin-left: 10px;margin-right: 10px;">
                        @if ($errors->any())
                            <div class="text-red-500 alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div>
                            <label class="block mb-2 text-sm font-medium text-white">Bingo Type</label>
                            <input type="text"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5 placeholder-gray-400"
                                placeholder="75 Bingo" style="width: 250px" disabled>
                        </div>
                        <div>
                            <label for="bet_amount" class="block mb-2 text-sm font-medium text-white">Bet amount</label>
                            <input type="text" id="bet_amount" name="bet_amount"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5 placeholder-gray-400"
                                placeholder="Enter bet amount" style="width: 250px"
                                value="{{ session('game_setup.bet_amount', '') }}" required>
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

                    <div class="grid gap-4 mb-4" style="margin-left: 10px;margin-right: 10px;">
                        <div>
                            <label for="call_speed" class="block mb-2 text-sm font-medium text-white">Call speed</label>
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
                                <option value="amharic_female">Amharic Female</option>
                                <option value="amharic_male">Amharic Male</option>
                                <option value="english_male">English Male</option>
                                <option value="english_female">English Female</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="selected_numbers" id="selected_numbers">
                    <input type="hidden" name="number_of_selected_numbers" id="number_of_selected_numbers">
                    <input type="hidden" name="total_amount" id="total_amount">

                    <button type="submit" style="background-color: #019DD6;"
                        class="w-full text-gray-100 font-bold py-2 px-4 rounded mt-4" style="margin-right: 100px">
                        Create Game
                    </button>
                </form>

                <button id="reset-button" class="w-full text-gray-100 font-bold py-2 px-4 rounded mt-4"
                    style="background-color: red;">Reset Board</button>
                <button id="announcementButton" class="w-full text-gray-100 font-bold py-2 px-4 rounded mt-4"
                    style="background-color: red;">Announcement</button>
            </div>
            <div class="pattern-display mt-6" style="text-align: center;">
                <div class="pattern-grid" id="pattern_grid">
                    <!-- The pattern grid will be generated here -->
                </div>
            </div>
        </div>

        {{-- <div class="pattern-display mt-6">
            <div class="pattern-grid" id="pattern_grid">
                <!-- The pattern grid will be generated here -->
            </div>
        </div> --}}

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
            const resetButton = document.getElementById('reset-button');
            const announcementButton = document.getElementById('announcementButton');
            const announcementSound = new Audio('/audios/announcement.mp3');

            announcementButton.addEventListener('click', function() {
                announcementSound.play().catch(e => {
                    console.error("Error playing sound:", e);
                    alert('Error playing sound. Please check console for details.');
                });
            });

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

                clearInterval(window.patternInterval);

                if (selectedOption.text === 'All Common Patterns') {
                    let patternIndex = 0;
                    displayPattern(patternData[patternIndex]);

                    window.patternInterval = setInterval(() => {
                        patternIndex = (patternIndex + 1) % patternData.length;
                        displayPattern(patternData[patternIndex]);
                    }, 3000);
                } else {
                    displayPattern(patternData);
                }
            });

            var toggleBtn = document.querySelector(
                '.dropdown-toggle');
            toggleBtn.addEventListener('click', toggleDropdown);

            function toggleDropdown() {
                var dropdownMenu = document.getElementById('dropdownMenu');
                if (dropdownMenu.classList.contains('hidden')) {
                    dropdownMenu.classList.remove('hidden');
                } else {
                    dropdownMenu.classList.add('hidden');
                }
            }

            // Close dropdown when clicking outside
            window.addEventListener('click', function(event) {
                if (!event.target.matches('.bg-gray-500') && !event.target.matches('.dropdown-toggle')) {
                    var dropdownMenu = document.getElementById('dropdownMenu');
                    if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
                        dropdownMenu.classList.add('hidden');
                    }
                }
            });

            function displayPattern(pattern) {
                patternGrid.innerHTML = '';
                const table = document.createElement('table');
                const headerRow = document.createElement('tr');
                const headers = ['B', 'I', 'N', 'G', 'O'];

                headers.forEach(header => {
                    const th = document.createElement('th');
                    th.textContent = header;
                    headerRow.appendChild(th);
                });
                table.appendChild(headerRow);

                pattern.forEach((row, rowIndex) => {
                    const tr = document.createElement('tr');
                    row.forEach((cell, cellIndex) => {
                        const td = document.createElement('td');
                        const div = document.createElement('div');
                        if (cell || (rowIndex === 2 && cellIndex === 2)) {
                            div.className = (rowIndex === 2 && cellIndex === 2) ? 'free-spot' :
                                'circle';
                        }
                        td.appendChild(div);
                        tr.appendChild(td);
                    });
                    table.appendChild(tr);
                });

                patternGrid.appendChild(table);
            }

            resetButton.addEventListener('click', function() {
                selectedNumbers = [];
                numberButtons.forEach(button => {
                    button.classList.remove('selected');
                });
                selectedNumbersInput.value = '';
                numberOfSelectedNumbersInput.value = 0;
                updateTotalAmount(); // Reset total amount
            });

            // Trigger change event to load the default pattern on page load
            winningPatternSelect.dispatchEvent(new Event('change'));
            updateTotalAmount(); // Update total amount on page load

        });
    </script>
    @vite('resources/js/app.js')
</body>

</html>
