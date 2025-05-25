@extends('layout.main')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 mx-auto">
    <div class="bg-white w-full max-w-md rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 text-white">
            <h2 class="text-2xl font-bold">Create New Account</h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('do-register') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Full Name
                    </label>
                    <input id="name" name="name" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="John Doe" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email Address
                    </label>
                    <input id="email" name="email" type="email"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="your@email.com" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input id="password" name="password" type="password"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                        Confirm Password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••" required>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="terms" class="rounded text-blue-600" required>
                        <span class="ml-2 text-sm text-gray-600">
                            I agree to the
                            <a href="" class="text-blue-600 hover:underline">Terms of Service</a>
                            and
                            <a href="" class="text-blue-600 hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                               transition duration-150 ease-in-out">
                    Register
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection