@extends('layouts.app')

@section('title', 'Support Center - MemeVault')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-600 to-purple-600 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">
                How Can We Help?
            </h1>
            <p class="text-xl text-white/90 mb-8">
                Get support from our team
                @auth
                    @if(auth()->user()->hasPrioritySupport())
                        <span class="inline-block px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-sm font-bold ml-2">
                            ⚡ Priority Support
                        </span>
                    @endif
                @endauth
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Create Ticket Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-black text-gray-900 mb-6">
                            Submit a Support Ticket
                        </h2>

                        <form action="{{ route('support.store') }}" method="POST" class="space-y-6">
                            @csrf

                            @guest
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        Email Address
                                    </label>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        required
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 text-gray-900"
                                        placeholder="your@email.com"
                                    >
                                </div>
                            @endguest

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Subject
                                </label>
                                <input 
                                    type="text" 
                                    name="subject" 
                                    required
                                    maxlength="255"
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 text-gray-900"
                                    placeholder="Brief description of your issue"
                                >
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Message
                                </label>
                                <textarea 
                                    name="message" 
                                    rows="6" 
                                    required
                                    minlength="10"
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 text-gray-900"
                                    placeholder="Describe your issue in detail..."
                                ></textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button 
                                type="submit"
                                class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-bold hover:from-primary-600 hover:to-primary-700 transition-colors"
                            >
                                Submit Ticket
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Response Time -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-blue-100 rounded-xl">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Response Time</h3>
                                <p class="text-sm text-gray-600">
                                    @auth
                                        @if(auth()->user()->isBusiness())
                                            Within 4 hours
                                        @elseif(auth()->user()->isPremium())
                                            Within 24 hours
                                        @else
                                            Within 48 hours
                                        @endif
                                    @else
                                        Within 48 hours
                                    @endauth
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Quick Links</h3>
                        <div class="space-y-3">
                            <a href="#" class="block text-primary-600 hover:underline">
                                📚 Documentation
                            </a>
                            <a href="#" class="block text-primary-600 hover:underline">
                                ❓ FAQ
                            </a>
                            <a href="#" class="block text-primary-600 hover:underline">
                                🎥 Video Tutorials
                            </a>
                            <a href="{{ route('pricing') }}" class="block text-primary-600 hover:underline">
                                💎 Upgrade for Priority Support
                            </a>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Other Ways to Reach Us</h3>
                        <div class="space-y-3 text-sm text-gray-600">
                            <p>📧 support@memevault.com</p>
                            <p>💬 Live Chat (Premium users)</p>
                            <p>📞 +1 (555) 123-4567</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Tickets -->
            @auth
                @if($tickets->count() > 0)
                    <div class="mt-12">
                        <h2 class="text-2xl font-black text-gray-900 mb-6">
                            My Support Tickets
                        </h2>

                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Ticket #</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Subject</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Status</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Priority</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Created</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($tickets as $ticket)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 text-sm font-mono text-gray-900">
                                                    {{ $ticket->ticket_number }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $ticket->subject }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold
                                                        {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                        {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-700' : '' }}
                                                    ">
                                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold
                                                        {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                                        {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                                        {{ $ticket->priority === 'normal' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $ticket->priority === 'low' ? 'bg-gray-100 text-gray-700' : '' }}
                                                    ">
                                                        {{ ucfirst($ticket->priority) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-600">
                                                    {{ $ticket->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('support.show', $ticket) }}" class="text-primary-600 hover:underline text-sm font-semibold">
                                                        View →
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</section>

@endsection