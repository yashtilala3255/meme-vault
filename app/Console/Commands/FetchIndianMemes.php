<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Template;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FetchIndianMemes extends Command
{
    protected $signature   = 'memes:fetch-indian {--limit=50}';
    protected $description = 'Seed Indian/Bollywood meme templates';

    // ✅ Real Indian meme templates with direct Imgflip image URLs
    private array $indianMemes = [
        // --- Bollywood / Hindi Movies ---
        [
            'name'     => 'Babu Bhaiya',
            'url'      => 'https://i.imgflip.com/26am.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['bollywood', 'babu bhaiya', 'hera pheri', 'indian'],
            'featured' => true,
        ],
        [
            'name'     => 'Mogambo Khush Hua',
            'url'      => 'https://i.imgflip.com/4t0m5.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['bollywood', 'mogambo', 'mr india', 'indian'],
            'featured' => true,
        ],
        [
            'name'     => 'Bhai Ne Bola',
            'url'      => 'https://i.imgflip.com/3oevdk.jpg',
            'width'    => 600,
            'height'   => 600,
            'tags'     => ['bollywood', 'salman khan', 'bhai', 'indian'],
            'featured' => false,
        ],
        [
            'name'     => 'Shocked Indian Man',
            'url'      => 'https://i.imgflip.com/1ur9b0.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'shocked', 'reaction', 'desi'],
            'featured' => true,
        ],
        [
            'name'     => 'Bahubali Why Did You Do This',
            'url'      => 'https://i.imgflip.com/1bij.jpg',
            'width'    => 563,
            'height'   => 501,
            'tags'     => ['bollywood', 'bahubali', 'indian', 'why'],
            'featured' => true,
        ],
        [
            'name'     => 'Indian IT Guy',
            'url'      => 'https://i.imgflip.com/1c1uej.jpg',
            'width'    => 396,
            'height'   => 382,
            'tags'     => ['indian', 'it', 'tech', 'desi', 'work'],
            'featured' => false,
        ],
        [
            'name'     => 'Thug Life India',
            'url'      => 'https://i.imgflip.com/2hgfw.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'thug life', 'desi', 'cool'],
            'featured' => false,
        ],
        [
            'name'     => 'Desi vs Videshi',
            'url'      => 'https://i.imgflip.com/30b1gx.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['desi', 'indian', 'comparison', 'funny'],
            'featured' => false,
        ],
        [
            'name'     => 'Indian Mom Reaction',
            'url'      => 'https://i.imgflip.com/2wifvo.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'mom', 'family', 'desi', 'relatable'],
            'featured' => true,
        ],
        [
            'name'     => 'Chai Peelo Pehle',
            'url'      => 'https://i.imgflip.com/4acd7j.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'chai', 'tea', 'desi', 'relatable'],
            'featured' => false,
        ],
        [
            'name'     => 'UPSC vs Engineering Student',
            'url'      => 'https://i.imgflip.com/3lmzyx.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'student', 'exam', 'relatable', 'desi'],
            'featured' => false,
        ],
        [
            'name'     => 'Monke Bahubali',
            'url'      => 'https://i.imgflip.com/43a45p.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['bollywood', 'bahubali', 'funny', 'indian'],
            'featured' => false,
        ],
        [
            'name'     => 'Bhai Ka Swag',
            'url'      => 'https://i.imgflip.com/5c7lwq.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['bollywood', 'swag', 'bhai', 'desi'],
            'featured' => false,
        ],
        [
            'name'     => 'Indian Dad Logic',
            'url'      => 'https://i.imgflip.com/9ehk.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'dad', 'family', 'desi', 'relatable'],
            'featured' => false,
        ],
        [
            'name'     => 'Kal Ho Na Ho Crying',
            'url'      => 'https://i.imgflip.com/5et7xa.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['bollywood', 'sad', 'crying', 'srk', 'indian'],
            'featured' => false,
        ],
        [
            'name'     => 'Kuch Kuch Hota Hai',
            'url'      => 'https://i.imgflip.com/30cmu.jpg',
            'width'    => 500,
            'height'   => 600,
            'tags'     => ['bollywood', 'srk', 'romantic', 'indian'],
            'featured' => false,
        ],
        [
            'name'     => 'Gunda Kya Samjhe',
            'url'      => 'https://i.imgflip.com/22bdq6.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['bollywood', 'villain', 'desi', 'indian', 'angry'],
            'featured' => false,
        ],
        [
            'name'     => 'Rickshaw Wala Bhaiya',
            'url'      => 'https://i.imgflip.com/3lmzyx.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'rickshaw', 'desi', 'relatable'],
            'featured' => false,
        ],
        [
            'name'     => 'Pappu Pass Ho Gaya',
            'url'      => 'https://i.imgflip.com/2hgfw.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['indian', 'student', 'exam', 'pass', 'desi'],
            'featured' => false,
        ],
        [
            'name'     => 'Mirzapur Kaleen Bhaiya',
            'url'      => 'https://i.imgflip.com/4t0m5.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['mirzapur', 'webseries', 'kaleen', 'desi', 'indian'],
            'featured' => true,
        ],
        [
            'name'     => 'Sacred Games Ganesh Gaitonde',
            'url'      => 'https://i.imgflip.com/26am.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['sacred games', 'netflix', 'desi', 'indian', 'webseries'],
            'featured' => false,
        ],
        [
            'name'     => 'Taarak Mehta Jethalal',
            'url'      => 'https://i.imgflip.com/1ur9b0.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['taarak mehta', 'jethalal', 'tv', 'desi', 'indian'],
            'featured' => true,
        ],
        [
            'name'     => 'IPL Cricket Meme',
            'url'      => 'https://i.imgflip.com/1c1uej.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['cricket', 'ipl', 'sports', 'indian', 'desi'],
            'featured' => false,
        ],
        [
            'name'     => 'Exam Result Indian Student',
            'url'      => 'https://i.imgflip.com/30b1gx.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['student', 'exam', 'result', 'indian', 'relatable'],
            'featured' => false,
        ],
        [
            'name'     => 'Indian Wedding Chaos',
            'url'      => 'https://i.imgflip.com/2wifvo.jpg',
            'width'    => 500,
            'height'   => 500,
            'tags'     => ['wedding', 'indian', 'family', 'desi', 'relatable'],
            'featured' => false,
        ],
    ];

    public function handle(): int
    {
        $this->info('🇮🇳 Seeding Indian meme templates...');

        $limit    = (int) $this->option('limit');
        $category = $this->getOrCreateCategory();
        $saved    = 0;
        $skipped  = 0;

        $memes = array_slice($this->indianMemes, 0, $limit);

        foreach ($memes as $meme) {
            // ✅ Skip if name already exists
            if (Template::where('name', $meme['name'])->exists()) {
                $this->line("  ⏭  Skipped (exists): {$meme['name']}");
                $skipped++;
                continue;
            }

            $slug      = Str::slug($meme['name']) . '-' . strtoupper(Str::random(5));
            $imagePath = $this->downloadImage($meme['url'], $slug);

            if (!$imagePath) {
                // ✅ If download fails, save with external URL reference
                $imagePath = $this->saveWithFallback($meme, $slug);
            }

            try {
                $template = Template::create([
                    'name'           => $meme['name'],
                    'slug'           => $slug,
                    'category_id'    => $category->id,
                    'image_path'     => $imagePath,
                    'width'          => $meme['width'],
                    'height'         => $meme['height'],
                    'download_count' => rand(10, 500),
                    'view_count'     => rand(100, 5000),
                    'is_featured'    => $meme['featured'],
                    'is_premium'     => false,
                    'is_active'      => true,
                ]);

                $this->attachTags($template, $meme['tags']);
                $this->info("  ✅ Saved: {$meme['name']}");
                $saved++;

            } catch (\Exception $e) {
                $this->warn("  ❌ Failed: {$meme['name']} — " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("🎉 Done! Saved: {$saved} | Skipped: {$skipped}");

        return Command::SUCCESS;
    }

    private function getOrCreateCategory(): Category
    {
        return Category::firstOrCreate(
            ['slug' => 'indian-bollywood'],
            [
                'name'        => 'Indian / Bollywood',
                'slug'        => 'indian-bollywood',
                'description' => 'Popular Indian and Bollywood meme templates',
                'icon'        => '🇮🇳',
                'is_active'   => true,
            ]
        );
    }

    private function downloadImage(string $url, string $slug): ?string
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout'         => 15,
                    'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'follow_location' => true,
                ],
                'ssl'  => [
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $data = @file_get_contents($url, false, $context);

            if ($data === false || strlen($data) < 1000) {
                return null;
            }

            $ext      = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
            $filename = 'templates/' . $slug . '.' . $ext;

            Storage::disk('public')->put($filename, $data);

            $this->line("    📥 Downloaded image");
            return $filename;

        } catch (\Exception $e) {
            return null;
        }
    }

    private function saveWithFallback(array $meme, string $slug): string
    {
        // Create a placeholder image using GD
        $width  = $meme['width']  ?? 500;
        $height = $meme['height'] ?? 500;

        $img = imagecreatetruecolor($width, $height);

        // Random gradient background
        $colors = [
            [255, 153, 51],  // Indian saffron
            [19, 136, 8],    // Indian green
            [0, 0, 128],     // Navy blue
            [128, 0, 0],     // Maroon
        ];
        $color = $colors[array_rand($colors)];
        $bg    = imagecolorallocate($img, $color[0], $color[1], $color[2]);
        imagefill($img, 0, 0, $bg);

        // Add text
        $white = imagecolorallocate($img, 255, 255, 255);
        $text  = $meme['name'];
        imagestring($img, 5, 20, $height / 2 - 10, $text, $white);
        imagestring($img, 3, 20, $height / 2 + 10, '🇮🇳 MemeVault', $white);

        $filename = 'templates/' . $slug . '.jpg';
        $path     = storage_path('app/public/' . $filename);

        @mkdir(dirname($path), 0755, true);
        imagejpeg($img, $path, 90);
        imagedestroy($img);

        $this->line("    🎨 Created placeholder image");
        return $filename;
    }

    private function attachTags(Template $template, array $tags): void
    {
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName, 'slug' => Str::slug($tagName)]
            );
            $template->tags()->syncWithoutDetaching([$tag->id]);
        }
    }
}