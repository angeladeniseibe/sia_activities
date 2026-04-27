<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto bg-white p-6 shadow rounded-lg">

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="border w-full p-2">
                </div>

                <div class="mb-4">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="border w-full p-2">
                </div>

                <button class="bg-blue-500 text-white px-4 py-2">
                    Update Profile
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
