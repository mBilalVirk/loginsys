<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css" />
</head>

@section('title', 'Login')

@extends('layouts.guest')

@section('content')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

        {{-- Toast-style error messages --}}
        @if ($errors->any())
            <div class="fixed top-4 right-4 z-50 space-y-2">
                @foreach ($errors->all() as $error)
                    <div
                        class="flex items-start gap-3 bg-white border-l-4 border-red-500 shadow-lg rounded-md px-4 py-3 max-w-sm">
                        <i class="pi pi-times-circle text-red-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Login Failed</p>
                            <p class="text-sm text-gray-500">{{ $error }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-black">
                Sign in to your account
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form method="POST" action="{{ route('loginUser') }}" autocomplete="off" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-black">
                        Email address
                    </label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" required autocomplete="email"
                            value="{{ old('email') }}"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm"
                            placeholder="you@example.com" />
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-black">
                            Password
                        </label>

                    </div>
                    <div class="mt-2 relative">
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm"
                            placeholder="••••••••" />
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-blue-600">
                            <i id="eye-icon" class="pi pi-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 transition-colors duration-150">
                        Sign in
                    </button>
                </div>
            </form>


            <p class="mt-3 text-center text-sm text-gray-400">
                <a href="{{ route('admin.login') }}" class="font-semibold text-indigo-400 hover:text-indigo-300">
                    Admin Login
                </a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.className = isHidden ? 'pi pi-eye-slash' : 'pi pi-eye';
        }
    </script>
@endsection
