<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Play Bingo!</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
                {{$win}}
            </div>

            <div class="display-panel">
                <div class="winning-amount">
                    <span>Winning Amount</span>
                    <div class="display-winning">{{ $winningAmount }}</div>
                </div>
                {{-- <div class="previous-call">
                    <span>Previous Call</span>
                    <div class="display">{{ $currentCall }}</div>
                </div> --}}
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
                        <div class="display">{{ $totalCalls }}</div>
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
                <form action="{{ route('bingo.check') }}" method="POST">
                    @csrf
                    <input type="text" id="name" name="name" required>
                    <button type="submit" class="btn">Check</button>
                </form>
                <form action="{{ route('bingo.call') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn" {{ $numbersAvailable ? '' : 'disabled' }}>Next
                        Number</button>
                </form>
                <form action="{{ route('bingo.reset') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">Reset Board</button>
                </form>
            </div>
        </div>
    </div>


    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
</body>

</html>
