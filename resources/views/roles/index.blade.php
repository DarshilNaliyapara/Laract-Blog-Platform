<x-app-layout>

    <div class="container mx-auto p-6 bg-gray-900 text-white rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-center">Users, Roles & Permissions</h2>

        <div class="mb-6 text-center">
            <a href="{{ route('users.index') }}"
                class="text-blue-400 hover:text-blue-300 text-lg mr-4 font-semibold transition">Refresh</a>
            <a href="{{ route('users.create') }}"
                class="text-green-400 hover:text-green-300 text-lg font-semibold transition">Create Roles</a>
        </div>

        <div class="overflow-x-auto bg-gray-800 p-4 rounded-lg shadow-md ">

            <x-text-input type="text" id="search" placeholder="Search..."
                class="justify-center dark:bg-gray-700 dark:text-gray-200 mr-2 mb-2" />
            <table class="w-full border border-gray-700 rounded-lg shadow-sm">
                <thead class="bg-gray-700 text-white text-md uppercase">
                    <tr>
                        <th class="border border-gray-700 px-6 py-3 text-left">Users</th>
                        <th class="border border-gray-700 px-6 py-3 text-left">Email</th>
                        <th class="border border-gray-700 px-6 py-3 text-left">Roles</th>
                        <th class="border border-gray-700 px-6 py-3 text-left">Permissions</th>
                        <th class="border border-gray-700 px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody id="userroles_table">
                    @foreach ($users as $user)
                        <tr class="usertable border-b border-gray-700 bg-gray-600 hover:bg-gray-500 transition">
                            <td class="username border border-gray-700 px-6 py-3 font-semibold text-white"
                                data-userid="{{ $user->id }}">
                                {{ $user->name }}
                            </td>
                            <td class="useremail border border-gray-700 px-6 py-3 font-semibold text-white"
                                data-userid="{{ $user->id }}">
                                {{ $user->email }}
                            </td>
                            <td class="userroles border border-gray-700 px-6 py-3 text-gray-300">
                                {{ $user->getRoleNames()->implode(', ') }}</td>
                            <td class="border border-gray-700 px-6 py-3 text-gray-300">
                                {{ $user->getPermissionsViaRoles()->pluck('name')->implode(', ') }}</td>
                            <td class="border border-gray-700 px-6 py-3">
                                @if ($user->id == 1)
                                    <p>user is Super Admin</p>
                                @else
                                    <form class="remove-role-form mb-2">
                                        <div class="remove-role-form-group hidden">
                                            @foreach ($user->roles as $role)
                                                <div class="form-check flex items-center gap-2 mb-1">
                                                    <input type="hidden" class="role-useremail"
                                                        value="{{ $user->email }}">

                                                    <input
                                                        class="form-check-input remove-role h-5 w-5 text-red-500 border-gray-400 rounded focus:ring focus:ring-red-500"
                                                        type="checkbox" name="roles[]" value="{{ $role->id }}">
                                                    <label
                                                        class="form-check-label text-gray-300">{{ $role->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button"
                                            class="btn btn-danger removerolesbtn bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                                            data-removeroles="{{ $user->id }}">
                                            Remove Role
                                        </button>
                                    </form>

                                    <form class="set-role-form">
                                        <div class="permissions form-group hidden">
                                            @foreach ($roles as $role)
                                                <div class="form-check flex items-center gap-2 mb-1">
                                                    <input type="hidden" class="role-useremail"
                                                        value="{{ $user->email }}">
                                                    <input
                                                        class="form-check-input set-role h-5 w-5 text-blue-500 border-gray-400 rounded focus:ring focus:ring-blue-500"
                                                        type="checkbox" name="roles[]" value="{{ $role->id }}">
                                                    <label
                                                        class="form-check-label text-gray-300">{{ $role->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button"
                                            class="btn btn-danger setrolesbtn bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                                            data-setroles="{{ $user->id }}">
                                            Set Role
                                        </button>
                                    </form>
                                    
                                    <x-danger-button x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                        class="mt-2">{{ __('Delete Account') }}</x-danger-button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form class="p-6 delete-user">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <button type="button"
                    class="ml-2 del-account-btn inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    data-deleteaccount="{{ $user->id }}">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
