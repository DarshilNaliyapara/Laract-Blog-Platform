<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg mt-7 rounded-lg">
        <form action="{{ route('users.createroles') }}" id="input" method="post" class="space-y-6">
            <h1 class="text-3xl font-bold text-gray-800 text-center">Create Roles and Permissions</h1>
            @csrf

            <h4 class="text-red-600 text-2xl text-center">Select Permissions Carefully!!</h4>

            <div class="clone bg-gray-100 p-6 rounded-lg">
                <!-- Role Input -->
                <div class="form-group mb-4">
                    <label for="role" class="block text-gray-700 font-medium mb-2">Role:</label>
                    <input type="text" name="role" id="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 outline-none">
                </div>

                <!-- Permissions -->
                <div class="form-group">
                    <label for="permissions" class="block text-gray-700 font-medium mb-2">Select Permissions:</label>
                    <div id="permissions" class="form-group grid grid-cols-2 gap-4 bg-white p-4 rounded-lg border">
                        @foreach ($permissions as $permission)
                            <div class="form-check flex items-center gap-2">
                                <input class="form-check-input h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring focus:ring-blue-300"
                                    type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    id="permission-{{ $permission->id }}">
                                <label class="form-check-label text-gray-700" for="permission-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submit-btn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                Create
            </button>

        </form>
    </div>
</x-app-layout>
