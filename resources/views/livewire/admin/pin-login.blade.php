<div class="min-h-screen bg-slate-900 flex items-center justify-center p-4 relative overflow-hidden">

    {{-- Background --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-900/20 to-slate-900"></div>
        <div class="absolute inset-0 opacity-20"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-indigo-400 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">

        {{-- Security Badge --}}
        <div class="flex justify-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800/50 backdrop-blur-sm rounded-full border border-slate-700">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium text-slate-300">SECURE CONNECTION • AES-256 ENCRYPTED</span>
            </div>
        </div>

        {{-- Card --}}
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition duration-1000"></div>

            <div class="relative bg-slate-800/90 backdrop-blur-xl rounded-2xl border border-slate-700 overflow-hidden">

                {{-- Card Header --}}
                <div class="relative h-32 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
                    <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full blur-md"></div>
                            <div class="relative w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center border-2 border-slate-700">
                                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 3L20 7V12C20 16.97 16.5 21.5 12 22C7.5 21.5 4 16.97 4 12V7L12 3Z"
                                          fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M12 12L16 10V14L12 16L8 14V10L12 12Z"
                                          fill="currentColor" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="pt-14 px-8 pb-8">
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-white mb-1">Administrator Access</h1>
                        <p class="text-sm text-slate-400">Enter your 6-digit PIN to access the control panel</p>
                    </div>

                    {{-- PIN Form --}}
                    <form wire:submit.prevent="authenticate" class="space-y-6">

                        {{-- PIN Input - Plain HTML, no Filament wrapper label --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-medium text-slate-400 uppercase tracking-wider">
                                Security PIN
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>

                                {{-- ✅ Direct wire:model binding — no Filament form wrapper --}}
                                <input
                                    type="password"
                                    wire:model="data.pin_code"
                                    placeholder="••••••"
                                    maxlength="6"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    autofocus
                                    @if($isLocked) disabled @endif
                                    class="w-full pl-12 pr-4 py-4 bg-slate-700/50 border border-slate-600
                                           text-white text-center text-2xl tracking-widest rounded-xl
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                           placeholder-slate-500 disabled:opacity-50 disabled:cursor-not-allowed
                                           transition-all duration-200"
                                >
                            </div>

                            {{-- Validation Error --}}
                            @error('data.pin_code')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Warning: attempts remaining --}}
                        @if($attempts > 0 && !$isLocked)
                            <div class="flex items-center gap-3 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                                <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-amber-400 font-medium">{{ 5 - $attempts }} attempt(s) remaining</p>
                                    <p class="text-xs text-amber-400/60 mt-0.5">Invalid PIN. Please try again.</p>
                                </div>
                            </div>
                        @endif

                        {{-- Locked message --}}
                        @if($isLocked)
                            <div class="flex items-center gap-3 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-red-400 font-medium">Account Temporarily Locked</p>
                                    <p class="text-xs text-red-400/60 mt-0.5">Too many failed attempts. Please wait 5 minutes.</p>
                                </div>
                            </div>
                        @endif

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            @if($isLocked) disabled @endif
                            class="relative w-full group overflow-hidden rounded-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative flex items-center justify-center gap-3 px-6 py-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                <span class="text-white font-semibold tracking-wide">Access Control Panel</span>
                            </div>
                        </button>

                        {{-- Security features row --}}
                        <div class="grid grid-cols-3 gap-3 pt-2">
                            @foreach([['2FA Ready','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'], ['PIN Protected','M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'], ['Auto-Lock','M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']] as [$label, $path])
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-8 h-8 bg-slate-700/50 rounded-full mb-2">
                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-slate-400">{{ $label }}</p>
                                </div>
                            @endforeach
                        </div>

                    </form>
                </div>

                {{-- Card Footer --}}
                <div class="px-8 py-4 bg-slate-900/50 border-t border-slate-700">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors group">
                            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Home
                        </a>
                        <span class="text-xs text-slate-500">v2.4.00</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security Notice --}}
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-500">All access attempts are logged and monitored</p>
            <p class="text-xs text-slate-600 mt-1">© {{ date('Y') }} Admin Portal</p>
        </div>
    </div>
</div>