<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Play Bingo!</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
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
                <h3>Winning Patterns</h3>
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
        <span class="bottom-right">
            <div class="control-panel">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo"> --}}
                <div class="display-panel">
                    <div class="total-calls">
                        <span>Total Calls</span>
                        <div name="total_calls" class="display">{{ $totalCalls }}</div>
                    </div>
                    {{-- <div class="previous-call">
                        <span>Previous Call</span>
                        <div class="display">{{ $currentCall }}</div>
                    </div> --}}
                </div>
                <div class="display-panel">
                    {{-- <div class="total-calls">
                        <span>Total Calls</span>
                        <div class="display">{{ $totalCalls }}</div>
                    </div> --}}
                    <div class="previous-call">
                        <span>Previous Call</span>
                        <div class="display">{{ $currentCall }}</div>
                    </div>
                </div>
                {{-- <div class="buttons">
                    <form action="{{ route('bingo.call') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn" {{ $numbersAvailable ? '' : 'disabled' }}>Next Number</button>
                    </form>
                    <form action="{{ route('bingo.reset') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn">Reset Board</button>
                    </form>
                </div> --}}
            </div>
        </span>

        <div class="control-panel">
            {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo"> --}}
            <div class="buttons">
                <div id="countdown-display" class="text-white mt-4"></div>

                <form action="{{ route('bingo.check') }}" method="POST">
                    @csrf
                    <input type="text" id="name" name="name" required>
                    <button type="submit" class="btn">Check</button>
                </form>
                {{-- <form action="{{ route('bingo.call') }}" method="POST" id="call-form">
                    @csrf
                    <button type="submit" class="btn" id="next-number-btn"
                        {{ $numbersAvailable ? '' : 'disabled' }}>Next Number</button>
                </form> --}}
                <form action="{{ route('bingo.call') }}" method="POST" id="call-form">
                    @csrf
                    <button type="submit" class="btn" id="next-number-btn"
                        {{ $numbersAvailable}}>Start Calling</button>
                </form>
                {{-- <form action="{{ route('bingo.reset') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">Reset Board</button>
                </form> --}}
                <form action="{{ route('bingo.end') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-end">End Game</button>
                </form>
                {{-- <audio id="audio" src="{{ asset('audios/Shefle.mp3') }}" autoplay="false"></audio>
                <button onclick="playSound();" class="btn">Shuffle</button> --}}
                {{-- <audio id="audio" src="{{ asset('audios/Shefle.mp3') }}" autoplay="false"></audio>
                <button id="shuffleButton" class="btn">Shuffle</button> --}}
            </div>
        </div>

        {{-- <script>
            document.getElementById("shuffleButton").addEventListener("click", function() {
                var sound = document.getElementById("audio");
                sound.play();
            });

        </script> --}}
        {{-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                const callSpeed = {{ session('caller_speed', 5000) }};
                const nextNumberBtn = document.getElementById('next-number-btn');

                // Automatically call the next number at the specified interval
                setInterval(() => {
                    if (!nextNumberBtn.disabled) {
                        document.getElementById('call-form').submit();
                    }
                }, callSpeed);
            });
        </script> --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let callSpeed = {{ session('caller_speed', 5000) }};
                let countdown = callSpeed / 1000;
                let intervalId;
                let isRunning = false;
                let totalCalls = {{ $totalCalls }}; // Fetch total calls from the server-side

                const nextNumberBtn = document.getElementById('next-number-btn');
                const countdownDisplay = document.getElementById('countdown-display');

                function startCalling() {
                    intervalId = setInterval(() => {
                        if (totalCalls >= 75) {
                            pauseCalling();
                            alert('Maximum number of calls reached.');
                            return;
                        }
                        countdown -= 1;
                        countdownDisplay.textContent = `Next call in: ${countdown}s`;
                        if (countdown <= 0) {
                            document.getElementById('call-form').submit();
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
                    countdownDisplay.textContent = `Next call in: ${countdown}s`;
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

                // Automatically start calling when the page loads
                startCalling();
                resetCountdown();
            });
        </script>




        {{-- <script>
            function playSound() {
                var sound = document.getElementById("audio");
                sound.play();
            }
        </script> --}}

    </div>


</body>

</html>
