<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Agent Account</title>
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
            <h1 class="text-3xl font-bold mb-6">Nexus Bingo</h1>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-lg hover:text-gray-400">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.manage-super-agent-account') }}"
                            class="text-lg hover:text-gray-400">Manage
                            Super Agent Account</a>
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
                        <a href="{{ route('bingo-cards.import-form') }}" class="text-lg hover:text-gray-400">Import
                            Bingo Cards</a>
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
                <h2 class="text-2xl font-bold">Manage Agent Account</h2>
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
                        <th>Super Agent</th>
                        <th>Owner Name</th>
                        <th>Address</th>
                        <th>Phone No</th>
                        <th>Current Balance</th>
                        <th>Top-up</th>
                        <th>Withdraw</th>
                        <th>Change Password</th>
                        <th>Assign</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowNumber = 1; @endphp
                    @foreach ($agents as $agent)
                        <tr>
                            <td>{{ $rowNumber++ }}</td>
                            <td>{{ $agent->agent->superAgent->user->name ?? 'No S.A.'}}</td>
                            <td>{{ $agent->name }}</td>
                            <td>{{ $agent->address }}</td>
                            <td>{{ $agent->phone }}</td>
                            <td>{{ $agent->current_balance ?? 0 }}</td>
                            <td>
                                <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setModalData('{{ $agent->id }}', '{{ $agent->name }}', '{{ $agent->current_balance ?? 0 }}')">Top-up</button>
                            </td>
                            <td>
                                <button data-modal-target="crud-modal-withdraw" data-modal-toggle="crud-modal-withdraw"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setModalDataWithdraw('{{ $agent->id }}', '{{ $agent->name }}', '{{ $agent->current_balance ?? 0 }}')">Withdraw</button>
                            </td>
                            <td>
                                <button data-modal-target="change-password-modal"
                                    data-modal-toggle="change-password-modal" class="text-blue-500 hover:text-blue-700"
                                    onclick="setChangePasswordModalData('{{ $agent->id }}', '{{ $agent->name }}')">Change
                                    Password</button>
                            </td>
                            <td>
                                {{-- <button data-modal-target="assign-modal" data-modal-toggle="assign-modal"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="setAssignModalData('{{ $agent->id }}', '{{ $agent->name }}')">Assign</button> --}}
                                <button data-modal-target="assign-modal" data-modal-toggle="assign-modal"
                                    onclick="setAssignModalData('{{ $agent->agent->id }}', '{{ $agent->name }}')"
                                    class="text-blue-500 hover:text-blue-700">Assign</button>
                                    {{-- {{dd($agent->agent->id) }} --}}

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $agents->links() }}
            </div>

            {{-- <a href="{{ route('agent.register') }}" class="mt-4 inline-block text-blue-500 hover:text-blue-700">Add
                New</a> --}}
            <a href="{{ route('agent.register') }}"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add
                New</a>

        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="change-password-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow dark:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Change Password for <span
                            id="AgentNamePassword">{{ $agent->name }}</span></h3>
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
                <form action="{{ route('admin.update-agent-password') }}" method="POST" class="p-4">
                    @csrf
                    <input type="hidden" name="agent_id" id="branchIdPassword">
                    <div>
                        <label for="newPassword" class="block text-sm font-medium text-white">New Password</label>
                        <input type="password" name="newPassword" id="newPassword"
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Update
                            Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function setChangePasswordModalData(branchId, AgentName) {
            document.getElementById('branchIdPassword').value = branchId;
            document.getElementById('AgentNamePassword').textContent = AgentName;
        }
    </script>


    <!-- Top-up Modal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="true"
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
                        data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.top-up-agent') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="agent_id" id="agentId">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="branchName"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agent
                                Name</label>
                            <input type="text" id="branchName"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                readonly>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="currentBalance"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current
                                Balance</label>
                            <input type="number" id="currentBalance"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                readonly>
                        </div>
                        <div class="col-span-2">
                            <label for="amount"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                            <input type="number" name="amount" id="amount"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded mr-2"
                            data-modal-toggle="crud-modal">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Topup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setModalData(agentId, branchName, currentBalance) {
            document.getElementById('agentId').value = agentId;
            document.getElementById('branchName').value = branchName;
            document.getElementById('currentBalance').value = currentBalance;
        }
    </script>

    <!-- Withdraw Modal -->
    <div id="crud-modal-withdraw" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow white:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Withdraw Branch
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="crud-modal-withdraw">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.withdraw-agent') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="agent_id" id="agentIdWithdraw">
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
                            data-modal-toggle="crud-modal-withdraw">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setModalDataWithdraw(agentId, branchName, currentBalance) {
            document.getElementById('agentIdWithdraw').value = agentId;
            document.getElementById('branchNameWithdraw').value = branchName;
            document.getElementById('currentBalanceWithdraw').value = currentBalance;
        }
    </script>

    <div id="assign-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-96 max-w-md md:max-w-sm max-h-full">
            <!-- Modal content -->
            <div class="relative bg-gray-500 rounded-lg shadow white:bg-gray-500">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Assign Agent
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
                <!-- Modal Form -->
                <form action="{{ route('admin.assign-agent') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="agent_id" id="agentIdAssign">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="agentNameAssign"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agent Name</label>
                            <input type="text" id="agentNameAssign"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                readonly>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="super_agentId"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Super
                                Agent</label>
                            <select name="super_agent_id" id="super_agentId"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                @foreach ($superAgents as $superAgent)
                                    <option value="{{ $superAgent->id }}">{{ $superAgent->user->name }}</option>
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
        function setAssignModalData(agentId, agentName) {
            console.log(agentId);

            document.getElementById('agentIdAssign').value = agentId;
            document.getElementById('agentNameAssign').value = agentName;
        }
    </script>


    @vite('resources/js/app.js')
</body>

</html>
