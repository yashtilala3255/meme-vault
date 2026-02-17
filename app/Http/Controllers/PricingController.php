<?php

namespace App\Http\Controllers;

class PricingController extends Controller
{
    public function index()
    {
        $plans = [
            'free' => [
                'name' => 'Free',
                'price' => 0,
                'features' => [
                    'Access to 1000+ templates',
                    'Basic meme editor',
                    'Download with watermark',
                    'Community support',
                    'Standard resolution'
                ],
                'limitations' => [
                    'Watermark on downloads',
                    'No premium templates',
                    'Community support only'
                ]
            ],
            'premium' => [
                'name' => 'Premium',
                'price_monthly' => 9.99,
                'price_yearly' => 99.99,
                'save_percent' => 17,
                'features' => [
                    'Everything in Free',
                    'No watermark on downloads',
                    'Access to premium templates',
                    'Priority support (24h response)',
                    'HD downloads (4K resolution)',
                    'Advanced editor tools',
                    'Ad-free experience',
                    'Early access to new templates'
                ],
                'popular' => true
            ],
            'business' => [
                'name' => 'Business',
                'price_monthly' => 29.99,
                'price_yearly' => 299.99,
                'save_percent' => 17,
                'features' => [
                    'Everything in Premium',
                    'White-label branding',
                    'Custom watermark',
                    'Team collaboration (5 users)',
                    'Priority support (4h response)',
                    'API access',
                    'Bulk downloads',
                    'Analytics dashboard',
                    'Dedicated account manager'
                ]
            ],
        ];

        return view('pricing', compact('plans'));
    }
}