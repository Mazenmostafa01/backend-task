<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Application</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="bg-gray-100">

    <!-- Navigation Bar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold">Your App</a>
            <div class="space-x-4">
                @guest
                    <!-- Sign In and Register Buttons for Guest Users -->
                    <a href="/login" class="bg-white-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Log In
                    </a>
                    <a href="#" class="bg-white-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Register
                    </a>
                @endguest

                @auth
                    <!-- Log Out Button for Authenticated Users -->
                    <form action="#" method="POST" class="inline">
                        @csrf
                        <x-button>Log Out</x-button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-4">
        {{ $slot }}
    </main>

</body>
</html>