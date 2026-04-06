<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css" />
</head>

@section('title', 'Register')

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
                            <p class="text-sm font-semibold text-gray-800">Registration Failed</p>
                            <p class="text-sm text-gray-500">{{ $error }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-black">
                Create your account
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form method="POST" action="{{ route('registerUser') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-black">
                        Full Name
                    </label>
                    <div class="mt-2">
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm"
                            placeholder="John Doe" />
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-black">
                        Email address
                    </label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm"
                            placeholder="you@example.com" />
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-black">
                        Password
                    </label>
                    <div class="mt-2 relative">
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm"
                            placeholder="••••••••" />
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-blue-600">
                            <i id="eye-icon" class="pi pi-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-black">
                        Confirm Password
                    </label>
                    <div class="mt-2">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm"
                            placeholder="••••••••" />
                    </div>
                </div>

                {{-- Gender --}}
                <div>
                    <label for="gender" class="block text-sm font-medium text-black">
                        Gender
                    </label>
                    <div class="mt-2">
                        <select id="gender" name="gender" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                {{-- DOB --}}
                <div>
                    <label for="dob" class="block text-sm font-medium text-black">
                        Date of Birth
                    </label>
                    <div class="mt-2">
                        <input id="dob" name="dob" type="date" required value="{{ old('dob') }}"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-blue-800 outline outline-1 -outline-offset-1 outline-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm" />
                    </div>
                </div>

                {{-- Photo --}}
                <div>
                    <label for="photo" class="block text-sm font-medium text-black">
                        Profile Photo
                    </label>
                    <div class="mt-2">
                        <input id="photo" name="photo" type="file" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    </div>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 transition-colors duration-150">
                        Register
                    </button>
                </div>
            </form>

            <p class="mt-3 text-center text-sm text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-indigo-400 hover:text-indigo-300">
                    Sign in here
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
