<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Play Bingo!</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
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
            /* margin-right: 00px; */
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
            background-color: #fff;
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
                width: 1.2rem;
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
    <div class="main-container">

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
                        @foreach ($win as $rowIndex => $row)
                            <tr>
                                @foreach ($row as $cellIndex => $cell)
                                    <td>
                                        @if ($rowIndex == 2 && $cellIndex == 2)
                                            <div class="free-spot"></div>
                                        @elseif ($cell)
                                            <div class="circle"></div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

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
    <div class="flex-container">
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
                        <div class="previous-call">
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
                {{-- <div id="countdown-display" class="text-white mt-4"></div> --}}
                <form action="{{ route('bingo.check') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="name" name="name" placeholder="Check Number" required>
                        <button type="button" class="btn-check">Check</button>
                    </div>

                    <!-- Modal Structure -->
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <p id="modal-text">Number check result will appear here.</p>
                        </div>
                    </div>

                </form>
                <form action="{{ route('bingo.call') }}" method="POST" id="call-form">
                    @csrf
                    <button type="submit" class="btn" id="next-number-btn" {{ $numbersAvailable }}>Start
                        Calling</button>
                </form>
                <div class="button-container">
                    <button id="shuffleButton" class="btn">Shuffle</button>
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

            const nextNumberBtn = document.getElementById('next-number-btn');
            const countdownDisplay = document.getElementById('countdown-display');
            const shuffleButton = document.getElementById('shuffleButton');
            const shuffleSound = new Audio('audios/Shuffle.mp3');
            var modal = document.getElementById("myModal");
            var btn = document.querySelector(".btn-check");
            var span = document.getElementsByClassName("close")[0];

            window.confirmEndGame = function() {
                var response = confirm('Are you sure you want to end the game?');
                if (response) {
                    document.getElementById('end-game-form').submit();
                } else {
                    console.log('Game end canceled by user.');
                }
            }

            // Display modal on button click
            btn.onclick = function() {
                var inputVal = document.getElementById("name").value;
                document.getElementById("modal-text").textContent = "Checking number: " + inputVal;
                modal.style.display = "block";
            }

            // Close the modal when the close button is clicked
            span.onclick = function() {
                modal.style.display = "none";
            }

            // Close the modal when clicking outside of the modal content
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            shuffleButton.addEventListener('click', function() {
                shuffleSound.play();
            });

            function startCalling() {
                intervalId = setInterval(() => {
                    if (totalCalls >= 75) {
                        pauseCalling();
                        alert('Maximum number of calls reached.');
                        return;
                    }
                    countdown -= 1;
                    countdownDisplay.textContent = `${countdown}s`;
                    if (countdown <= 0) {
                        fetchNextNumber();
                    }
                }, 1000);
                isRunning = true;
                nextNumberBtn.textContent = 'Pause';
            }

            function pauseCalling() {
                clearInterval(intervalId);
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
                updateCallDisplay(data.callHistory); // Make sure this function exists and is correct

                if (newNumber !== null) {
                    const audio = new Audio(`/audios/${newNumber}.mp3`);
                    audio.play();
                }
                resetCountdown(); // Reset the countdown after fetching the number
            }

            function updateBoard(callHistory) {
                const bingoButtons = document.querySelectorAll('.bingo-table button');
                const totalCallsDisplay = document.querySelector('.total-calls .display');
                if (totalCallsDisplay) {
                    totalCallsDisplay.textContent = totalCalls;
                }

                // Update previous calls display
                const previousCallsDisplay = document.querySelector('.previous-call .display');
                if (previousCallsDisplay && callHistory.length) {
                    previousCallsDisplay.textContent = callHistory[callHistory.length - 1];
                }
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
