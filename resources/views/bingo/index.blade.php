<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LET'S PLAY BINGO!</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-black text-white">
    {{-- <h1 class="text-center">LET'S PLAY BINGO!</h1>
    <div class="text-center">
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
    </div> --}}
    @include('bingo.partials.board', ['callHistory' => $callHistory])
</body>
</html>
