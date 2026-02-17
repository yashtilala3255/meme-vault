@extends('layouts.app')

@section('title', 'Pricing Plans - MemeVault')

@section('content')

<!-- Hero Section -->
<section class="relative py-20 overflow-hidden bg-gradient-to-br from-primary-600 via-purple-600 to-secondary-600">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl animate-float"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl animate-float animation-delay-2000"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-lg rounded-full text-white font-bold text-sm mb-6">
                💎 PREMIUM PLANS
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6">
                Choose Your Plan
            </h1>
            <p class="text-xl md:text-2xl text-white/90 mb-8">
                Unlock premium features and create memes without limits
            </p>
            
            <!-- Billing Toggle -->
            <div class="inline-flex items-center bg-white/10 backdrop-blur-lg rounded-full p-2 mb-8">
                <button id="monthly-btn" class="px-6 py-3 rounded-full font-bold text-white bg-white/20 transition-all" onclick="toggleBilling('monthly')">
                    Monthly
                </button>
                <button id="yearly-btn" class="px-6 py-3 rounded-full font-bold text-white/70 transition-all" onclick="toggleBilling('yearly')">
                    Yearly
                    <span class="ml-2 px-2 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-black">Save 17%</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Cards -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-7xl mx-auto">
            
            <!-- Free Plan -->
            <div class="bg-white rounded-3xl shadow-lg p-8 border-2 border-gray-200 hover:shadow-2xl transition-all">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <span class="text-3xl">🎨</span>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Free</h3>
                    <div class="text-5xl font-black text-gray-900 mb-2">
                        $0
                        <span class="text-lg font-normal text-gray-500">/forever</span>
                    </div>
                    <p class="text-gray-600">Perfect for casual users</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    @foreach($plans['free']['features'] as $feature)
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </li>
                    @endforeach
                    @foreach($plans['free']['limitations'] as $limitation)
                        <li class="flex items-start gap-3 opacity-50">
                            <svg class="w-6 h-6 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-400 line-through">{{ $limitation }}</span>
                        </li>
                    @endforeach
                </ul>
                
                <a href="{{ route('templates.index') }}" class="block w-full py-4 bg-gray-200 text-gray-900 text-center rounded-2xl font-bold hover:bg-gray-300 transition-colors">
                    Get Started Free
                </a>
            </div>

            <!-- Premium Plan -->
            <div class="relative bg-gradient-to-br from-primary-500 to-primary-600 rounded-3xl shadow-2xl p-8 transform scale-105 border-4 border-yellow-400">
                <!-- Popular Badge -->
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="inline-block px-6 py-2 bg-yellow-400 text-yellow-900 rounded-full text-sm font-black shadow-lg">
                        ⭐ MOST POPULAR
                    </span>
                </div>
                
                <div class="text-center mb-8 mt-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 backdrop-blur-lg mb-4">
                        <span class="text-3xl">💎</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Premium</h3>
                    <div class="text-5xl font-black text-white mb-2">
                        <span class="monthly-price">${{ $plans['premium']['price_monthly'] }}</span>
                        <span class="yearly-price hidden">${{ number_format($plans['premium']['price_yearly'] / 12, 2) }}</span>
                        <span class="text-lg font-normal text-white/80">/month</span>
                    </div>
                    <p class="text-white/80 yearly-price hidden">Billed ${!! $plans['premium']['price_yearly'] !!} yearly</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    @foreach($plans['premium']['features'] as $feature)
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-yellow-300 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-white">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
                
                @auth
                    <form action="{{ route('subscribe', 'premium') }}" method="POST">
                        @csrf
                        <input type="hidden" name="billing_cycle" id="premium-billing-cycle" value="monthly">
                        <button type="submit" class="block w-full py-4 bg-white text-primary-600 text-center rounded-2xl font-black text-lg hover:bg-gray-100 transition-colors shadow-lg">
                            Upgrade to Premium
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="block w-full py-4 bg-white text-primary-600 text-center rounded-2xl font-black text-lg hover:bg-gray-100 transition-colors shadow-lg">
                        Get Premium
                    </a>
                @endauth
            </div>

            <!-- Business Plan -->
            <div class="bg-white rounded-3xl shadow-lg p-8 border-2 border-gray-200 hover:shadow-2xl transition-all">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 mb-4">
                        <span class="text-3xl">🚀</span>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Business</h3>
                    <div class="text-5xl font-black text-gray-900 mb-2">
                        <span class="monthly-price">${{ $plans['business']['price_monthly'] }}</span>
                        <span class="yearly-price hidden">${{ number_format($plans['business']['price_yearly'] / 12, 2) }}</span>
                        <span class="text-lg font-normal text-gray-500">/month</span>
                    </div>
                    <p class="text-gray-600 yearly-price hidden">Billed ${{ $plans['business']['price_yearly'] }} yearly</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    @foreach($plans['business']['features'] as $feature)
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
                
                @auth
                    <form action="{{ route('subscribe', 'business') }}" method="POST">
                        @csrf
                        <input type="hidden" name="billing_cycle" id="business-billing-cycle" value="monthly">
                        <button type="submit" class="block w-full py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-center rounded-2xl font-bold hover:from-purple-600 hover:to-pink-600 transition-colors">
                            Get Business
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="block w-full py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-center rounded-2xl font-bold hover:from-purple-600 hover:to-pink-600 transition-colors">
                        Get Business
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Features Comparison -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-4xl font-black text-gray-900 text-center mb-12">
                Compare All Features
            </h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Feature</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Free</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Premium</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Business</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">Templates Access</td>
                            <td class="px-6 py-4 text-center">1000+</td>
                            <td class="px-6 py-4 text-center">2000+</td>
                            <td class="px-6 py-4 text-center">All</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">Watermark</td>
                            <td class="px-6 py-4 text-center">✅</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">❌</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">HD Downloads</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">✅</td>
                            <td class="px-6 py-4 text-center">✅ 4K</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">Priority Support</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">24h</td>
                            <td class="px-6 py-4 text-center">4h</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">Ad-Free Experience</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">✅</td>
                            <td class="px-6 py-4 text-center">✅</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">White-Label Branding</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">✅</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">API Access</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">✅</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">Team Collaboration</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">❌</td>
                            <td class="px-6 py-4 text-center">5 users</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-black text-gray-900 text-center mb-12">
                Frequently Asked Questions
            </h2>
            
            <div class="space-y-6">
                <details class="group bg-white rounded-2xl p-6 shadow-lg">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-gray-900">
                        <span>Can I cancel my subscription anytime?</span>
                        <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600">
                        Yes! You can cancel your subscription at any time. You'll continue to have access to premium features until the end of your billing period.
                    </p>
                </details>

                <details class="group bg-white rounded-2xl p-6 shadow-lg">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-gray-900">
                        <span>What happens to my memes if I downgrade?</span>
                        <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600">
                        All memes you've created will remain accessible. However, premium features like watermark removal will only apply to future downloads if you're on a paid plan.
                    </p>
                </details>

                <details class="group bg-white rounded-2xl p-6 shadow-lg">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-gray-900">
                        <span>Is there a free trial?</span>
                        <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600">
                        Our Free plan is essentially a permanent trial! You can try all basic features before deciding to upgrade.
                    </p>
                </details>

                <details class="group bg-white rounded-2xl p-6 shadow-lg">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-gray-900">
                        <span>What payment methods do you accept?</span>
                        <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600">
                        We accept all major credit cards (Visa, Mastercard, American Express) and PayPal through our secure payment processor Stripe.
                    </p>
                </details>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-secondary-600">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
            Ready to Create Amazing Memes?
        </h2>
        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Join thousands of creators who are already making viral memes with MemeVault
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-primary-600 rounded-2xl font-black text-lg hover:bg-gray-100 transition-colors">
                Get Started Free
            </a>
            <a href="{{ route('templates.index') }}" class="px-8 py-4 bg-white/10 backdrop-blur-lg text-white rounded-2xl font-bold text-lg hover:bg-white/20 transition-colors border-2 border-white">
                Browse Templates
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function toggleBilling(cycle) {
        const monthlyBtn = document.getElementById('monthly-btn');
        const yearlyBtn = document.getElementById('yearly-btn');
        const monthlyPrices = document.querySelectorAll('.monthly-price');
        const yearlyPrices = document.querySelectorAll('.yearly-price');
        const premiumBillingInput = document.getElementById('premium-billing-cycle');
        const businessBillingInput = document.getElementById('business-billing-cycle');
        
        if (cycle === 'monthly') {
            monthlyBtn.classList.add('bg-white/20');
            monthlyBtn.classList.remove('text-white/70');
            yearlyBtn.classList.remove('bg-white/20');
            yearlyBtn.classList.add('text-white/70');
            
            monthlyPrices.forEach(el => el.classList.remove('hidden'));
            yearlyPrices.forEach(el => el.classList.add('hidden'));
            
            if (premiumBillingInput) premiumBillingInput.value = 'monthly';
            if (businessBillingInput) businessBillingInput.value = 'monthly';
        } else {
            yearlyBtn.classList.add('bg-white/20');
            yearlyBtn.classList.remove('text-white/70');
            monthlyBtn.classList.remove('bg-white/20');
            monthlyBtn.classList.add('text-white/70');
            
            monthlyPrices.forEach(el => el.classList.add('hidden'));
            yearlyPrices.forEach(el => el.classList.remove('hidden'));
            
            if (premiumBillingInput) premiumBillingInput.value = 'yearly';
            if (businessBillingInput) businessBillingInput.value = 'yearly';
        }
    }
</script>
@endpush

@endsection