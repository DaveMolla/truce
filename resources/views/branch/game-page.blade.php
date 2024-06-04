<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Page | Bingo</title>
    <!-- Ensure the path here is correct -->
    {{-- @vite('resources/css/app.css') --}}
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background-color: #232223;
    color: #fff;
    margin: 0;
    padding: 20px;
    text-align: center;
}

header h1 {
    color: #1899d4;
    font-style: bold;
}

.main-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.bingo-table {
    margin: 20px auto;
    border-collapse: collapse;
    width: 80%;
}

.bingo-table th, .bingo-table td {
    padding: 10px;
    text-align: center;
}

.bingo-table th {
    padding-top: 30px;
    padding-bottom: 30px;
    background-color: #fff;
    font-size: 40px;
    color: #1899d4;
    font-weight: 1000;
}

.bingo-table td {
    font-size: 28px;
    background-color: #191918;
    color: white;
}

.bingo-table td button {
    background: none;
    border: none;
    color: #464646;
    font-size: inherit;
    cursor: pointer;
    padding: 10px;
    font-weight: 1000;
}

.called {
    color: white !important;
}

.control-panel {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 20px;
}

.logo {
    width: 400px;
    margin-bottom: 20px;
    margin-top: -50px;
}

.display-panel {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.control-panel .total-calls, .control-panel .previous-call {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.control-panel .display {
    background-color: #000;
    border: 2px solid #0af;
    font-size: 36px;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
}

.control-panel .buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.control-panel .btn {
    background-color: #0af;
    border: none;
    color: #000;
    padding: 10px 20px;
    font-size: 20px;
    cursor: pointer;
    border-radius: 5px;
}

.control-panel .btn:hover {
    background-color: #0099cc;
}

.winning-patterns {
    margin-top: 20px;
    text-align: left;
}

.winning-patterns h3 {
    margin-bottom: 10px;
}

.winning-patterns ul {
    list-style: none;
    padding: 0;
}

.winning-patterns li {
    margin-bottom: 5px;
}

    </style>
</head>
<body class="bg-gray-800 text-white">

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div class="mb-3 text-gray-100 ">LETS PLAY BINGO <br>
        <div class="pattern-grid" id="pattern_grid">
            <!-- The pattern grid will be generated here -->
        </div></div>

        <div class="main-container">
            <div class="control-panel">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="logo">
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
                        <button type="submit" class="btn">Next Number</button>
                    </form>
                    <form action="{{ route('bingo.reset') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn">Reset Board</button>
                    </form>
                </div>
                <div class="winning-patterns">
                    <h3>Winning Patterns</h3>
                    <ul>
                        @if(is_array($winningPatterns) && count($winningPatterns) > 0)
                            @foreach ($winningPatterns as $pattern)
                                <li>{{ $pattern }}</li>
                            @endforeach
                        @else
                            <li>No winning patterns set</li>
                        @endif
                    </ul>
                </div>
            </div>
            <table class="bingo-table">
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
        </div>
</div>
<button class="btn btn-danger">Primary Button</button>
<button class="btn btn-primary">Primary Button</button>
@vite(['resources/css/bootstrap.css', 'resources/js/app.js'])

</body>
</html>
