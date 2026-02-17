@extends('layouts.app')

@section('title', 'Forgot Password - MemeVault')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8">

            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3 mb-6">
                    <div class="bg-gradient-to-br from-primary-500 to-secondary-500 p-3 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                    </div>
                    <span class="text-3xl font-black bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        MemeVault
                    </span>
                </a>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Forgot Password?</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    No worries! Enter your email and we'll send you a reset link.
                </p>
            </div>

            @if(session('status'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-400 rounded-xl text-green-700 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white transition-colors"
                        placeholder="your@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-black text-lg hover:from-primary-600 hover:to-primary-700 transition-colors shadow-lg">
                    Send Reset Link
                </button>

                <p class="text-center text-gray-600 dark:text-gray-400">
                    Remember your password?
                    <a href="{{ route('login') }}" class="font-bold text-primary-600 dark:text-primary-400 hover:underline ml-1">
                        Back to login
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection