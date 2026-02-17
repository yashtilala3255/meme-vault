@extends('layouts.app')

@section('title', 'Confirm Password - MemeVault')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white">Confirm Password</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Please confirm your password before continuing.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-black text-lg hover:from-primary-600 hover:to-primary-700 transition-colors">
                    Confirm
                </button>
            </form>
        </div>
    </div>
</div>
@endsection