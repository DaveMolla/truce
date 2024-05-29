<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Branch Account</title>
    @vite('resources/css/app.css')
    <style>
        th,
        td {
            padding: 12px 15px;
            border: 1px solid #444;
        }

        th {
            background-color: #555;
            text-align: left;
        }

        td {
            background-color: #666;
        }
    </style>
</head>

<body class="bg-gray-800 text-white">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-700 w-64 p-4">
            <h1 class="text-3xl font-bold mb-6">Truce Bingo</h1>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-lg hover:text-gray-400">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-agent-account') }}" class="text-lg hover:text-gray-400">Manage
                            Agent Account</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-branch-account') }}" class="text-lg hover:text-gray-400">Manage
                            Branch Account</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('bingo-cards.import-form') }}" class="text-lg hover:text-gray-400">Import Bingo Cards</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.cards') }}" class="text-lg hover:text-gray-400">Boards</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Manage Branch Account</h2>
                <div class="bg-blue-500 p-3 rounded-full">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white">Logout</button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table content here -->
            <table class="min-w-full bg-gray-700">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Owner Name</th>
                        <th>Address</th>
                        <th>Phone No</th>
                        <th>Current Balance</th>
                        <th>Top-up</th>
                        <th>Withdraw</th>
                        <th>Change Password</th>
                        <th>Set Cutoff Percent</th>
                        <th>Assign</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowNumber = 1; @endphp
                    @foreach ($branches as $branch)
                        <tr>
                            <td>{{ $rowNumber++ }}</td>
                            <td>{{ $branch->user->name }}</td>
                            <td>{{ $branch->user->address }}</td>
                            <td>{{ $branch->user->phone }}</td>
                            <td>{{ $branch->user->current_balance ?? 0 }}</td>
                            <td>
                                <button data-modal-target="topup-modal" data-modal-toggle="topup-modal"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setTopupModalData('{{ $branch->id }}', '{{ $branch->user->name }}', '{{ $branch->user->current_balance ?? 0 }}')">Top-up</button>
                            </td>
                            <td>
                                <button data-modal-target="withdraw-modal" data-modal-toggle="withdraw-modal"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setWithdrawModalData('{{ $branch->id }}', '{{ $branch->user->name }}', '{{ $branch->user->current_balance ?? 0 }}')">Withdraw</button>
                            </td>
                            <td>
                                <button data-modal-target="change-password-modal"
                                    data-modal-toggle="change-password-modal" class="text-blue-500 hover:text-blue-700"
                                    onclick="setChangePasswordModalData('{{ $branch->id }}', '{{ $branch->user->name }}')">Change
                                    Password</button>
                            </td>
                            <td>
                                <button data-modal-target="set-cutoff-modal" data-modal-toggle="set-cutoff-modal"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setCutoffModalData('{{ $branch->id }}', '{{ $branch->user->name }}', '{{ $branch->user->cut_off_percent}}')">Set
                                    Cutoff Percent</button>
                            </td>
                            <td>
                                <button data-modal-target="assign-modal" data-modal-toggle="assign-modal"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setAssignModalData('{{ $branch->id }}', '{{ $branch->user->name }}')">Assign</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $branches->links() }}
            </div>

            <a href="{{ route('branch.register') }}"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add
                New</a>
        </div>
    </div>


    <!-- Set Cutoff Percent Modal -->
    <div id="set-cutoff-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow dark:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Set Cutoff Percent for <span
                            id="branchNameCutoff">[Branch Name]</span></h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="set-cutoff-modal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.set-cutoff') }}" method="POST" class="p-4">
                    @csrf
                    <input type="hidden" name="branch_id" id="branchIdCutoff">
                    <div>
                        <label for="cutoffPercent" class="block text-sm font-medium text-white">Cutoff Percent</label>
                        <input type="number" name="cutoffPercent" id="cutoffPercent"
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Set
                            Cutoff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setCutoffModalData(branchId, branchName, currentCutoff) {
            document.getElementById('branchIdCutoff').value = branchId;
            document.getElementById('branchNameCutoff').textContent = branchName;
            document.getElementById('cutoffPercent').value = currentCutoff;
        }
    </script>



    <!-- Change Password Modal -->
    <div id="change-password-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow dark:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Change Password for <span
                            id="branchNamePassword">{{ $branch->user->name }}</span></h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="change-password-modal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.update-branch-password') }}" method="POST" class="p-4">
                    @csrf
                    <input type="hidden" name="branch_id" id="branchIdPassword">
                    <div>
                        <label for="newPassword" class="block text-sm font-medium text-white">New Password</label>
                        <input type="password" name="newPassword" id="newPassword"
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>
                    <div class="mt-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Update
                            Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function setChangePasswordModalData(branchId, branchName) {
            document.getElementById('branchIdPassword').value = branchId;
            document.getElementById('branchNamePassword').textContent = branchName;
        }
    </script>

    <!-- Top-up Modal -->
    <div id="topup-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow white:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Topup Branch
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="topup-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.top-up-branch') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="branch_id" id="branchIdTopup">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="branchNameTopup"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Branch
                                Name</label>
                            <input type="text" id="branchNameTopup"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                readonly>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="currentBalanceTopup"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current
                                Balance</label>
                            <input type="number" id="currentBalanceTopup"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                readonly>
                        </div>
                        <div class="col-span-2">
                            <label for="amountTopup"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                            <input type="number" name="amount" id="amountTopup"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
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

    <script>
        function setTopupModalData(branchId, branchName, currentBalance) {
            document.getElementById('branchIdTopup').value = branchId;
            document.getElementById('branchNameTopup').value = branchName;
            document.getElementById('currentBalanceTopup').value = currentBalance;
        }
    </script>

    <!-- Withdraw Modal -->
    <div id="withdraw-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-96 max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow white:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Withdraw Branch
                    </h3>
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
                <form action="{{ route('admin.withdraw-branch') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="branch_id" id="branchIdWithdraw">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="branchNameWithdraw"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Branch
                                Name</label>
                            <input type="text" id="branchNameWithdraw"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                readonly>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="currentBalanceWithdraw"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current
                                Balance</label>
                            <input type="number" id="currentBalanceWithdraw"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                readonly>
                        </div>
                        <div class="col-span-2">
                            <label for="amountWithdraw"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                            <input type="number" name="amount" id="amountWithdraw"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
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
        function setWithdrawModalData(branchId, branchName, currentBalance) {
            document.getElementById('branchIdWithdraw').value = branchId;
            document.getElementById('branchNameWithdraw').value = branchName;
            document.getElementById('currentBalanceWithdraw').value = currentBalance;
        }
    </script>

    <!-- Assign Modal -->
    <div id="assign-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-96 max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow white:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Assign Branch
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="assign-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.assign-branch') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="branch_id" id="branchIdAssign">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="branchNameAssign"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Branch
                                Name</label>
                            <input type="text" id="branchNameAssign"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                readonly>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="agentId"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                                Agent</label>
                            <select name="agent_id" id="agentId"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded mr-2"
                            data-modal-toggle="assign-modal">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setAssignModalData(branchId, branchName) {
            document.getElementById('branchIdAssign').value = branchId;
            document.getElementById('branchNameAssign').value = branchName;
        }
    </script>

    @vite('resources/js/app.js')
</body>

</html>
