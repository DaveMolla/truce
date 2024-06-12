<!-- resources/views/agent/branches.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch List | Nexus Agent</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-900 text-white">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Nexus Bingo</h1>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('agent.dashboard') }}" class="text-lg hover:text-gray-400">Branches</a>
                    </li>
                    {{-- <li class="mb-4">
                        <a href="{{ route('agent.branches') }}" class="text-lg hover:text-gray-400">Branches</a>
                    </li> --}}
                </ul>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Branches</h2>
                <div class="bg-blue-500 p-3 rounded-full">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Agent Balance -->
            <div class="bg-gray-800 p-4 rounded-lg mb-8">
                @if (session('success'))
                    <div class="bg-green-500 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-gray-800 text-red p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                <h3 class="text-xl font-bold">Agent Balance: {{ Auth::user()->current_balance ?? 0.0 }}</h3>
            </div>

            <!-- Table content here -->
            <table class="min-w-full bg-gray-800 rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">City</th>
                        <th class="px-4 py-2">Phone Number</th>
                        <th class="px-4 py-2">Balance</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rowNumber = 1;
                    @endphp
                    @foreach ($branches as $branch)
                        <tr>
                            <td class="border px-4 py-2">{{ $rowNumber++ }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->name }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->address }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->phone }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->current_balance ?? 0 }}</td>
                            <td class="border px-4 py-2">{{ $branch->user->created_at->format('M jS y') }}</td>
                            <td class="border px-4 py-2">
                                <button data-modal-target="topup-modal" data-modal-toggle="topup-modal"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setTopupModalData('{{ $branch->id }}', '{{ $branch->user->name }}', '{{ $branch->user->current_balance ?? 0 }}')">Topup</button> /
                                <button data-modal-target="withdraw-modal" data-modal-toggle="withdraw-modal"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="setWithdrawModalData('{{ $branch->id }}', '{{ $branch->user->name }}', '{{ $branch->user->current_balance ?? 0 }}')">Withdraw</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $branches->links() }}
            </div>
        </div>
    </div>

    <!-- Top-up Modal -->
    <div id="topup-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-800 rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Topup Branch</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="topup-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('agent.top-up-branch') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="branch_id" id="branchIdTopup">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="branchNameTopup" class="block mb-2 text-sm font-medium text-white">Branch
                                Name</label>
                            <input type="text" id="branchNameTopup"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                readonly>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="currentBalanceTopup" class="block mb-2 text-sm font-medium text-white">Current
                                Balance</label>
                            <input type="number" id="currentBalanceTopup"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                readonly>
                        </div>
                        <div class="col-span-2">
                            <label for="amountTopup" class="block mb-2 text-sm font-medium text-white">Amount</label>
                            <input type="number" name="amount" id="amountTopup"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded mr-2"
                            data-modal-toggle="topup-modal">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Topup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div id="withdraw-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-800 rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Withdraw Branch</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="withdraw-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('agent.withdraw-branch') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="branch_id" id="branchIdWithdraw">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="branchNameWithdraw" class="block mb-2 text-sm font-medium text-white">Branch
                                Name</label>
                            <input type="text" id="branchNameWithdraw"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                readonly>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="currentBalanceWithdraw"
                                class="block mb-2 text-sm font-medium text-white">Current Balance</label>
                            <input type="number" id="currentBalanceWithdraw"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                readonly>
                        </div>
                        <div class="col-span-2">
                            <label for="amountWithdraw"
                                class="block mb-2 text-sm font-medium text-white">Amount</label>
                            <input type="number" name="amount" id="amountWithdraw"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded mr-2"
                            data-modal-toggle="withdraw-modal">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setTopupModalData(branchId, branchName, currentBalance) {
            document.getElementById('branchIdTopup').value = branchId;
            document.getElementById('branchNameTopup').value = branchName;
            document.getElementById('currentBalanceTopup').value = currentBalance;
        }

        function setWithdrawModalData(branchId, branchName, currentBalance) {
            document.getElementById('branchIdWithdraw').value = branchId;
            document.getElementById('branchNameWithdraw').value = branchName;
            document.getElementById('currentBalanceWithdraw').value = currentBalance;
        }
    </script>

    @vite('resources/js/app.js')
</body>

</html>
