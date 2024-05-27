
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truce Bingo Agent Login</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-800 flex items-center justify-center min-h-screen">

    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-md">
        {{-- <div class="flex justify-center mb-6">
            <img src="/path/to/your/logo.png" alt="Truce Bingo" class="h-12">
        </div> --}}
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Sign in to your account</h2>

        <form method="POST" action="{{ route('agent.login') }}" class="max-w-sm mx-auto">
            @csrf

            <div class="mb-5">
                <label for="phone" class="block mb-2 text-sm font-medium text-white">Phone Number</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autofocus
                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400"
                    placeholder="090123456">
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-white">Your password</label>
                <input id="password" type="password" name="password" required
                    class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400"
                    placeholder="••••••••">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- <div class="flex items-start mb-5">
                <div class="flex items-center h-5">
                    <input id="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-700 focus:ring-3 focus:ring-blue-300 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                </div>
                <label for="remember" class="ms-2 text-sm font-medium text-gray-300">Remember me</label>
            </div> --}}

            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Sign
                in</button>
        </form>
    </div>

    @vite('resources/js/app.js')
</body>

</html>
