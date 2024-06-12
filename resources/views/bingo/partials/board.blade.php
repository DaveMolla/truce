<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Play Bingo!</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')

    <style>
        .winning-pattern-images {
            position: relative;
            max-width: 100%;
        }

        .winning-pattern-images .slide {
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
        }

        .bingo-card-table {
            width: 90%;
            /* Full width of the container */
            border-collapse: collapse;
            /* Collapses the border between cells */
            margin-top: 20px;
            /* Space above the table */
            background-color: #f8f9fa;
            /* Light grey background */
            align-items: center;
            margin-left: 100px;
            height: 90%;
            /* background-color: #007bff */
            font-size: 60px;
            font-weight: bold;
        }

        .bingo-card-table th {
            background-color: #007bff;
            /* Blue background for headers */
            color: white;
            /* White text color */
            font-weight: bold;
            /* Bold font for headers */
            padding: 12px;
            /* Padding inside the header cells */
            border: 1px solid #dee2e6;
            /* Light grey border */
        }

        .bingo-card-table td {
            text-align: center;
            /* Center-align text */
            padding: 10px;
            /* Padding inside cells */
            border: 1px solid #dee2e6;
            /* Light grey border */
            background-color: #ffffff;
            /* White background for cells */
            color: black;
        }

        #bkg {
            background-color: #899dbe
        }

        .message {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            background-color: #ddd
        }

        .bingo-card-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
            /* Zebra striping for rows */
        }

        .bingo-card-table tbody tr:hover {
            background-color: #ddd;
            /* Hover effect for rows */
        }

        .bingo-card-table td.called,
        .bingo-card-table td.center-spot {
            background-color: #007bff;
            /* Blue background for called numbers and the center spot */
            color: white;
            /* White text for better visibility */
        }

        .flex-container {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .last-five-calls {
            flex: 1;
            margin-right: 20px;
        }

        .control-panel-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-right: 30px;
        }

        .display-panel {
            display: flex;
            flex-direction: column;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            margin-left: 20px;
        }

        .bingo-pattern {
            background-color: #232223;
            width: 270px;
            height: 270px;
            max-width: 300px;
            border-collapse: collapse;
            margin: 0 auto;
        }

        .bingo-pattern th,
        .bingo-pattern td {
            width: 2rem;
            height: 2rem;
            text-align: center;
            border: 5px solid #044472;
        }

        .bingo-pattern .circle {
            width: 1.8rem;
            height: 1.8rem;
            background-color: #019DD6;
            border-radius: 50%;
            margin: 0 auto;
        }

        .bingo-pattern .free-spot {
            background-color: rgb(241, 241, 96);
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            margin: 0 auto;
        }

        .winning-amount {
            margin-top: 40px;
        }

        .bingo-pattern-header th {
            background-color: #044472;
            color: white;
            font-weight: bold;
        }

        .bingo-pattern-header th:nth-child(1),
        .bingo-pattern-header th:nth-child(2),
        .bingo-pattern-header th:nth-child(3),
        .bingo-pattern-header th:nth-child(4),
        .bingo-pattern-header th:nth-child(5) {
            background-color: #044472;
        }

        @media (max-width: 768px) {

            .bingo-pattern th,
            .bingo-pattern td {
                width: 1.5rem;
                height: 1.5rem;
                border: 3px solid #044472;
            }

            .bingo-pattern .circle {
                width: 1.8rem;
                height: 1.2rem;
            }

            .bingo-pattern .free-spot {
                width: 1.2rem;
                height: 1.2rem;
            }
        }

        @media (max-width: 480px) {

            .bingo-pattern th,
            .bingo-pattern td {
                width: 1.2rem;
                height: 1.2rem;
                border: 2px solid #044472;
            }

            .bingo-pattern .circle,
            .bingo-pattern .free-spot {
                width: 1rem;
                height: 1rem;
            }
        }
    </style>

    {{-- <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}"></head> --}}

<body>
    <div class="main-container" style="background-color: #232223">

        <table class="bingo-table">
            <tbody>
                @php
                    $groups = [
                        'B' => range(1, 15),
                        'I' => range(16, 30),
                        'N' => range(31, 45),
                        'G' => range(46, 60),
                        'O' => range(61, 75),
                    ];

                    // Mapping numbers to their corresponding letter
                    $numberToLetterMap = [];
                    foreach ($groups as $letter => $numbers) {
                        foreach ($numbers as $number) {
                            $numberToLetterMap[$number] = $letter;
                        }
                    }
                @endphp
                @foreach ($groups as $letter => $numbers)
                    <tr>
                        <th>{{ $letter }}</th>
                        @foreach ($numbers as $number)
                            <td>
                                <button class="{{ in_array($number, $callHistory ?? []) ? 'called' : '' }}">
                                    {{ $number }}
                                </button>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="control-panel">
            {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <div class="display-panel">
                <div class="total-calls">
                    <span>Total Calls</span>
                    <div class="display">{{ $totalCalls }}</div>
                </div>
                <div class="previous-call">
                    <span>Previous Call</span>
                    <div class="display">{{ $currentCall }}</div>
                </div>
            </div>
            <div class="buttons">
                <form action="{{ route('bingo.call') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn" {{ $numbersAvailable ? '' : 'disabled' }}>Next Number</button>
                </form>
                <form action="{{ route('bingo.reset') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">Reset Board</button>
                </form>
            </div> --}}
            <div class="winning-patterns">
                <table class="bingo-pattern">
                    <thead class="bingo-pattern-header">
                        <tr>
                            <th class="B">B</th>
                            <th class="I">I</th>
                            <th class="N">N</th>
                            <th class="G">G</th>
                            <th class="O">O</th>
                        </tr>
                    </thead>
                    <tbody>

                        <div id="slideshow-container" class="winning-pattern-images">
                            @foreach ($winningPattern->images as $image)
                                <img src="{{ asset($image->image_path) }}" class="slide" style="display: none;"
                                    alt="Winning Pattern Image">
                            @endforeach
                        </div>

                    </tbody>
                </table>
            </div>

            <div class="display-panel">
                <div class="winning-amount-box">
                    <span class="winning-amharic">ደራሽ</span>
                    <div class="display-winning">{{ $winningAmount }}</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Add last five calls display below the table -->
    <div class="flex-container" style="background-color: #232223">
        <div class="last-five-calls">
            @php
                $lastFiveCalls = array_slice($callHistory, -6);
                $colors = [
                    'B' => 'ball-blue',
                    'I' => 'ball-red',
                    'N' => 'ball-orange',
                    'G' => 'ball-green',
                    'O' => 'ball-yellow',
                ];
            @endphp
            @foreach (array_reverse($lastFiveCalls) as $number)
                @php
                    $letter = $numberToLetterMap[$number];
                    $isCurrentCall = $number === $currentCall;
                @endphp
                <div class="previous-number {{ $colors[$letter] }} {{ $isCurrentCall ? 'current-call' : '' }}">
                    <span>{{ $letter }}<br>{{ $number }}</span>
                </div>
            @endforeach
        </div>

        <div class="control-panel-group">
            <span class="bottom-right">
                <div class="control-panel">
                    <div class="display-panel">
                        <div class="countdown">
                            <span>Next call</span>
                            <div id="countdown-display" class="display"></div>
                        </div>
                        <div class="total-calls">
                            <span>Total Calls</span>
                            <div name="total_calls" class="display">{{ $totalCalls }}</div>
                        </div>
                    </div>
                </div>
            </span>
            <div class="buttons">
                @if (session('error'))
                    <div class="text-red-500">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('bingo.check') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="cardId" name="card_id" placeholder="Check Board"
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5 placeholder-gray-400"
                            required>
                        <button type="submit" class="btn-check">Check</button>
                    </div>
                </form>


                <!-- Modal Structure -->
                @if (session('show_modal'))
                    <div id="check-number" tabindex="-1" aria-hidden="true"
                        class="fixed inset-0 z-50 flex items-center justify-center w-full h-full">
                        <div class="relative p-4 w-full max-w-3xl h-auto">
                            <div class="relative bg-white rounded-lg shadow">
                                <div class="flex justify-between items-center p-5 rounded-t border-b">
                                    <h3 class="text-xl font-medium text-gray-900">Check Card</h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                        onclick="document.getElementById('check-number').style.display='none';">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-6 space-y-6" id="bkg">
                                    @if (session('card_data'))
                                        <table class="bingo-card-table">
                                            <thead>
                                                <div class="message">
                                                    @if (session('has_won'))
                                                        <p class="text-green-500">Card {{ session('card_id') }} ->
                                                            ዘግቷል!</p>
                                                        <audio id="success-audio" src="{{ asset('audios/win.mp3') }}"
                                                            autoplay></audio>
                                                    @else
                                                        <p class="text-red-500">Card {{ session('card_id') }} -> አልዘጋም!
                                                        </p>
                                                        <audio id="fail-audio" src="{{ asset('audios/not-win.mp3') }}"
                                                            autoplay></audio>
                                                    @endif
                                                </div>

                                                <tr>
                                                    <th style="background-color: #3B82F6">B</th>
                                                    <th style="background-color: #EF4444">I</th>
                                                    <th style="background-color: #F97316">N</th>
                                                    <th style="background-color: #22C55E">G</th>
                                                    <th style="background-color: #EAB308">O</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $rowCount = 0; @endphp
                                                @foreach (session('card_data', []) as $row)
                                                    @php $cellCount = 0; @endphp
                                                    <tr>
                                                        @foreach ($row as $cell)
                                                            @php
                                                                $isCalled = in_array(
                                                                    $cell,
                                                                    session('called_numbers', []),
                                                                );
                                                                $isCenter = $rowCount == 2 && $cellCount == 2;
                                                            @endphp
                                                            <td
                                                                class="{{ $isCalled ? 'called' : '' }} {{ $isCenter ? 'center-spot' : '' }}">
                                                                {{ $isCenter ? 'FREE' : $cell }}
                                                            </td>
                                                            @php $cellCount++; @endphp
                                                        @endforeach
                                                    </tr>
                                                    @php $rowCount++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-base leading-relaxed text-gray-500">No card data found.</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endif


                <!-- Modal Toggle Script -->
                <script>
                    function toggleModal() {
                        var modal = document.getElementById('check-number');
                        modal.classList.toggle('hidden');
                    }
                </script>


                {{-- @if (session('modal'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var modal = document.getElementById('myModal');
                            document.getElementById('modal-text').textContent = "{{ session('modal') }}";
                            modal.style.display = "block";

                            window.onclick = function(event) {
                                if (event.target == modal) {
                                    modal.style.display = 'none';
                                }
                            }
                        });
                    </script>
                @endif --}}
                <audio id="pauseAudio" src="audios/Bingo.mp3" preload="auto"></audio>
                <audio id="startAudio" src="audios/Start.mp3" preload="auto"></audio>


                <form action="{{ route('bingo.call') }}" method="POST" id="call-form">
                    @csrf
                    <button type="submit" class="btn-start" id="next-number-btn" {{ $numbersAvailable }}>Start
                        Calling</button>
                </form>
                <div class="button-container">
                    <button id="shuffleButton" class="btn-shuffle">Shuffle</button>
                    <form action="{{ route('bingo.end') }}" method="POST" id="end-game-form">
                        @csrf
                        <button type="button" class="btn-end" onclick="confirmEndGame()">End Game</button>
                    </form>
                </div>



            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let callSpeed = {{ session('caller_speed', 5000) }};
            let countdown = callSpeed / 1000;
            let intervalId;
            let isRunning = false;
            let totalCalls = {{ $totalCalls }}; // Fetch total calls from the server-side
            let isFetching = false; // Flag to indicate if a number fetch is in progress


            const nextNumberBtn = document.getElementById('next-number-btn');
            const countdownDisplay = document.getElementById('countdown-display');
            const shuffleButton = document.getElementById('shuffleButton');
            const shuffleSound = new Audio('audios/Shuffle.mp3');
            const slides = document.querySelectorAll('#slideshow-container .slide');
            const numberButtons = document.querySelectorAll('.bingo-table td button');
            const pauseAudio = document.getElementById('pauseAudio');
            const startAudio = document.getElementById('startAudio');
            const gameSetup = @json(session('game_setup', []));
            // console.log(gameSetup);
            let activeInterval;

            let numberActivationCounts = new Map(); // To track activations for each number
            let totalActivationsNeeded = 10; // Each number needs to be active twice


            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.display = (i === index) ? 'block' : 'none';
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            showSlide(currentSlide);
            setInterval(nextSlide, 3000);

            window.addEventListener('DOMContentLoaded', (event) => {
                const successAudio = document.getElementById('success-audio');
                const failAudio = document.getElementById('fail-audio');

                if (successAudio) {
                    successAudio.play();
                } else if (failAudio) {
                    failAudio.play();
                }
            });

            // Close modal on clicking outside of the modal content
            window.onclick = function(event) {
                if (event.target == document.getElementById('check-number')) {
                    closeModal();
                }
            };

            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") { // Check if the key pressed is 'Escape'
                    closeModal();
                }
            });

            function closeModal() {
                document.getElementById('check-number').style.display = "none";
            }



            window.confirmEndGame = function() {
                var response = confirm('Are you sure you want to end the game?');
                if (response) {
                    document.getElementById('end-game-form').submit();
                } else {
                    console.log('Game end canceled by user.');
                }
            }


            function shuffleActiveNumbers() {
                // Reset the active class for all buttons at the start of each shuffle
                numberButtons.forEach(button => {
                    button.classList.remove('active');
                });

                const activeCount = 25 + Math.floor(Math.random() * 6); // Randomly choose between 5 to 10 numbers
                const shuffledNumbers = Array.from(numberButtons);
                shuffleArray(shuffledNumbers);

                shuffledNumbers.slice(0, activeCount).forEach(button => {
                    button.classList.add('active');
                    let activations = numberActivationCounts.get(button.textContent) || 0;
                    numberActivationCounts.set(button.textContent, ++
                        activations); // Increment the activation count
                });

                // Check if all numbers have been activated at least twice
                if (Array.from(numberActivationCounts.values()).every(count => count >= totalActivationsNeeded)) {
                    clearInterval(activeInterval); // Stop the interval when all numbers have been activated twice
                    console.log('All numbers have been activated twice.');
                    numberButtons.forEach(button => {
                        button.classList.remove('active');
                    });
                }
            }

            shuffleButton.addEventListener('click', function() {
                shuffleSound.play();
                clearInterval(activeInterval); // Clear any existing interval
                numberActivationCounts.clear(); // Reset activation counts
                shuffleActiveNumbers(); // Initial shuffle when button is clicked
                activeInterval = setInterval(shuffleActiveNumbers,
                    75); // Shuffle active numbers every 2 seconds
            });

            function shuffleArray(array) {
                for (let i = array.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [array[i], array[j]] = [array[j], array[i]]; // Swap elements
                }
            }

            function startCalling() {
                if (!intervalId) { // Ensure that no interval is running before starting a new one
                    startAudio.play(); // Play start sound

                    startAudio.onended = function() { // When the start sound ends, start the countdown
                        intervalId = setInterval(() => {
                            if (totalCalls >= 75) {
                                pauseCalling();
                                alert('Maximum number of calls reached.');
                                return;
                            }
                            if (countdown > 0) {
                                countdown -= 1;
                                countdownDisplay.textContent = `${countdown}s`;
                            }
                            if (countdown <= 0 && !
                                isFetching) { // Only fetch next number if not already fetching
                                fetchNextNumber();
                            }
                        }, 1000);
                        isRunning = true;
                        nextNumberBtn.textContent = 'Pause';
                    };
                }
            }

            function pauseCalling() {
                if (intervalId) {
                    pauseAudio.play(); // Play pause sound
                    clearInterval(intervalId);
                    intervalId = null; // Clear the interval ID
                }
                isRunning = false;
                nextNumberBtn.textContent = 'Start';
            }

            function resetCountdown() {
                countdown = callSpeed / 1000;
                countdownDisplay.textContent = `${countdown}s`;
            }

            nextNumberBtn.addEventListener('click', function(event) {
                event.preventDefault();
                if (isRunning) {
                    pauseCalling();
                } else {
                    startCalling();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.code === 'Space') {
                    event.preventDefault();
                    if (isRunning) {
                        pauseCalling();
                    } else {
                        startCalling();
                    }
                }
            });

            async function fetchNextNumber() {
                if (!isFetching) {
                    isFetching = true; // Set flag to true to indicate fetch in progress
                    const response = await fetch("{{ route('bingo.call') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    const newNumber = data.number;
                    totalCalls = data.totalCalls;
                    updateBoard(data.callHistory);
                    updateCallDisplay(data.callHistory);

                    if (data.number !== null) {
                        const callerLanguage = gameSetup.caller_language || 'default_language';
                        const audioPath = `/audios/${callerLanguage}/${data.number}.mp3`;
                        const audio = new Audio(audioPath);
                        audio.play().catch(e => console.error('Error playing audio:', e));
                    }
                    resetCountdown(); // Reset the countdown after fetching the number
                    isFetching = false; // Reset flag after fetch completes
                }
            }

            function updateBoard(callHistory) {
                const bingoButtons = document.querySelectorAll('.bingo-table button');
                const totalCallsDisplay = document.querySelector('.total-calls .display');
                if (totalCallsDisplay) {
                    totalCallsDisplay.textContent = totalCalls;
                }

                // Update previous calls display
                const countdownDisplay = document.querySelector('.countdown .display');
                // if (countdownDisplay && callHistory.length) {
                //     countdownDisplay.textContent = callHistory[callHistory.length - 1];
                // }
                bingoButtons.forEach(button => {
                    const number = parseInt(button.textContent, 10);
                    if (callHistory.includes(number)) {
                        button.classList.add('called');
                    } else {
                        button.classList.remove('called');
                    }
                });
            }

            function updateCallDisplay(callHistory) {
                const previousNumbersContainer = document.querySelector('.last-five-calls');
                if (!previousNumbersContainer) {
                    console.error("Last five calls container not found");
                    return;
                }
                // Clear only the calls, not the entire display
                previousNumbersContainer.innerHTML = '';

                callHistory.slice(-6).reverse().forEach((number, index) => {
                    const letter = getLetterForNumber(number);
                    const colorClass = `ball-${getColorForLetter(letter)}`;
                    const isCurrentCall = index === 0; // First element is the current call

                    const callDiv = document.createElement('div');
                    callDiv.className =
                        `previous-number ${colorClass} ${isCurrentCall ? 'current-call' : ''}`;
                    callDiv.innerHTML = `<span>${letter}<br>${number}</span>`;
                    previousNumbersContainer.appendChild(callDiv);
                });
            }

            // Make sure this function accurately reflects your bingo logic
            function getLetterForNumber(number) {
                if (number <= 15) return 'B';
                if (number <= 30) return 'I';
                if (number <= 45) return 'N';
                if (number <= 60) return 'G';
                if (number <= 75) return 'O';
                return '?';
            }

            function getColorForLetter(letter) {
                const colors = {
                    'B': 'blue',
                    'I': 'red',
                    'N': 'orange',
                    'G': 'green',
                    'O': 'yellow',
                };
                return colors[letter] || 'default';
            }


            resetCountdown();
        });
    </script>

</body>

</html>
