<x-layout>
    <section>
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
            <h1 class="text-2xl font-bold mb-4">Log In</h1>
            <form action="/api/login" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded">
                </div>
                @error('email')
                    <p class="font-bold text-red-500">{{$message}}</p>
                @enderror
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded">
                </div>
                @error('password')
                    <p class="font-bold text-red-500 mb-3">{{$message}}</p>
                @enderror
                <x-button>Log In</x-button>
            </form>
        </div>  
    </section>
</x-layout>