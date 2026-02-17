<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Reaction Memes',
                'slug'        => 'reaction',
                'icon'        => '😮',
                'order'       => 1,
            ],
            [
                'name'        => 'Advice Animals',
                'slug'        => 'advice-animals',
                'icon'        => '🐾',
                'order'       => 2,
            ],
            [
                'name'        => 'Funny',
                'slug'        => 'funny',
                'icon'        => '😂',
                'order'       => 3,
            ],
            [
                'name'        => 'Trending',
                'slug'        => 'trending',
                'icon'        => '🔥',
                'order'       => 4,
            ],
            [
                'name'        => 'Gaming',
                'slug'        => 'gaming',
                'icon'        => '🎮',
                'order'       => 5,
            ],
            [
                'name'        => 'Sports',
                'slug'        => 'sports',
                'icon'        => '⚽',
                'order'       => 6,
            ],
            [
                'name'        => 'Politics',
                'slug'        => 'politics',
                'icon'        => '🏛️',
                'order'       => 7,
            ],
            [
                'name'        => 'TV & Movies',
                'slug'        => 'tv-movies',
                'icon'        => '🎬',
                'order'       => 8,
            ],
            [
                'name'        => 'Animals',
                'slug'        => 'animals',
                'icon'        => '🐶',
                'order'       => 9,
            ],
            [
                'name'        => 'Wholesome',
                'slug'        => 'wholesome',
                'icon'        => '🥰',
                'order'       => 10,
            ],
            [
                'name'        => 'Dark Humor',
                'slug'        => 'dark-humor',
                'icon'        => '💀',
                'order'       => 11,
            ],
            [
                'name'        => 'Relationship',
                'slug'        => 'relationship',
                'icon'        => '💕',
                'order'       => 12,
            ],
            [
                'name'        => 'Work & Office',
                'slug'        => 'work-office',
                'icon'        => '💼',
                'order'       => 13,
            ],
            [
                'name'        => 'School & College',
                'slug'        => 'school-college',
                'icon'        => '📚',
                'order'       => 14,
            ],
            // ✅ NEW - Indian categories
            [
                'name'        => 'Indian / Bollywood',
                'slug'        => 'indian-bollywood',
                'icon'        => '🇮🇳',
                'order'       => 15,
            ],
            [
                'name'        => 'Desi Memes',
                'slug'        => 'desi-memes',
                'icon'        => '🙏',
                'order'       => 16,
            ],
        ];

        foreach ($categories as $category) {
            // ✅ firstOrCreate - skips if slug already exists, never throws duplicate error
            Category::firstOrCreate(
                ['slug' => $category['slug']],  // find by slug
                $category                        // create with these values if not found
            );
        }

        $this->command->info('✅ Categories seeded successfully! (' . count($categories) . ' total)');
    }
}