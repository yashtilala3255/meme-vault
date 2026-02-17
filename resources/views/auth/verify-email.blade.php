@extends('layouts.app')

@section('title', 'Verify Email - MemeVault')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 text-center">

            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-100 dark:bg-primary-900 mb-6">
                <svg class="w-10 h-10 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-3">Verify Your Email</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Thanks for signing up! Please verify your email address by clicking the link we just sent you.
            </p>

            @if(session('status') === 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 rounded-xl text-green-700 dark:text-green-300 font-semibold">
                    A new verification link has been sent to your email.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-black hover:from-primary-600 hover:to-primary-700 transition-colors">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection