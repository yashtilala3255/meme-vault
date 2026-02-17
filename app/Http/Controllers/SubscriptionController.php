<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request, $tier)
    {
        $request->validate([
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $user = Auth::user();
        
        // Check if user already has an active subscription
        if ($user->activeSubscription) {
            return redirect()->back()->with('error', 'You already have an active subscription.');
        }

        // Pricing
        $prices = [
            'premium' => [
                'monthly' => 9.99,
                'yearly' => 99.99,
            ],
            'business' => [
                'monthly' => 29.99,
                'yearly' => 299.99,
            ],
        ];

        $billingCycle = $request->billing_cycle;
        $price = $prices[$tier][$billingCycle];

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'tier' => $tier,
            'billing_cycle' => $billingCycle,
            'price' => $price,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => $billingCycle === 'monthly' ? now()->addMonth() : now()->addYear(),
        ]);

        // Update user
        $user->update([
            'subscription_tier' => $tier,
            'premium_expires_at' => $subscription->expires_at,
        ]);

        return redirect()->route('subscription.show')->with('success', 'Successfully subscribed to ' . ucfirst($tier) . ' plan!');
    }

    public function show()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        return view('subscription.show', compact('user', 'subscription'));
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        if (!$subscription) {
            return redirect()->back()->with('error', 'No active subscription found.');
        }

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Subscription cancelled. You will have access until ' . $subscription->expires_at->format('M d, Y'));
    }
}