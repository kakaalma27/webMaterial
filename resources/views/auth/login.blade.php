@extends('layout.main')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 mx-auto">
    <div class="bg-white w-full max-w-md rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 text-white">
            <h2 class="text-2xl font-bold">Login to Your Account</h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('do-login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email Address
                    </label>
                    <input id="email" name="email" type="email"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="your@email.com" required autofocus>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input id="password" name="password" type="password"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded text-blue-600">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="" class="text-sm text-blue-600 hover:underline">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                               transition duration-150 ease-in-out">
                    Login
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">
                        Register here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection