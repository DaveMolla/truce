<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Play Bingo!</title>
    <link rel="stylesheet" href="app.css">
</head>
<body>
    {{-- <header>
        <h1>LET'S PLAY BINGO!</h1>
    </header> --}}
    <table class="bingo-table">
        <thead>
        </thead>
        <tbody>
            @php
                $groups = [
                    'B' => range(1, 15),
                    'I' => range(16, 30),
                    'N' => range(31, 45),
                    'G' => range(46, 60),
                    'O' => range(61, 75)
                ];
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
        {{-- <button onclick="nextNumber()">Next Number</button>
        <button onclick="resetBoard()">Reset Board</button>
        <div>Total Calls: <span id="totalCalls">0</span></div>
        <div>Previous Call: <span id="previousCall">None</span></div> --}}
        <div>Total Calls: {{ $totalCalls }}</div>
        <div>Previous Call: {{ $currentCall }}</div>
        <form action="{{ route('bingo.call') }}" method="POST">
            @csrf
            <button type="submit">Next Number</button>
        </form>
        <form action="{{ route('bingo.reset') }}" method="POST">
            @csrf
            <button type="submit">Reset Board</button>
        </form>
    </div>
</body>
</html>
