<?php

namespace App\Console\Commands;

use App\Models\Template;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FetchMemesFromImgflip extends Command
{
    protected $signature = 'memes:fetch-imgflip {--limit=100} {--force}';
    protected $description = 'Fetch meme templates from Imgflip API';

    public function handle()
    {
        $this->info('🚀 Fetching memes from Imgflip API...');
        $this->newLine();

        try {
            // Fetch memes from Imgflip API
            $response = Http::timeout(30)->get('https://api.imgflip.com/get_memes');

            if (!$response->successful()) {
                $this->error('❌ Failed to fetch memes from Imgflip API');
                return 1;
            }

            $data = $response->json();
            
            if (!isset($data['success']) || !$data['success']) {
                $this->error('❌ API returned unsuccessful response');
                return 1;
            }

            $memes = $data['data']['memes'];
            $limit = $this->option('limit');
            $force = $this->option('force');
            
            $this->info("📊 Found " . count($memes) . " memes from Imgflip");
            $this->info("📥 Importing up to {$limit} templates...");
            $this->newLine();

            $imported = 0;
            $skipped = 0;
            $failed = 0;

            $progressBar = $this->output->createProgressBar(min($limit, count($memes)));
            $progressBar->start();

            foreach (array_slice($memes, 0, $limit) as $index => $meme) {
                try {
                    // Skip if already exists (unless force flag is used)
                    $existingTemplate = Template::where('name', $meme['name'])->first();
                    
                    if ($existingTemplate && !$force) {
                        $skipped++;
                        $progressBar->advance();
                        continue;
                    }

                    if ($existingTemplate && $force) {
                        $existingTemplate->delete();
                    }

                    // Download image from Imgflip
                    $imageResponse = Http::timeout(20)->get($meme['url']);
                    
                    if (!$imageResponse->successful()) {
                        $failed++;
                        $progressBar->advance();
                        continue;
                    }

                    $imageContent = $imageResponse->body();
                    
                    // Generate filename
                    $imageName = Str::slug($meme['name']) . '-' . $meme['id'] . '.jpg';
                    $imagePath = 'templates/' . $imageName;
                    
                    // Save image to storage
                    Storage::disk('public')->put($imagePath, $imageContent);

                    // Determine category based on meme name and characteristics
                    $category = $this->getCategoryFromMeme($meme);

                    // Generate tags based on meme name
                    $tags = $this->generateTags($meme['name']);

                    // Create template
                    $template = Template::create([
                        'name' => $meme['name'],
                        'slug' => Str::slug($meme['name']) . '-' . Str::random(5),
                        'category_id' => $category->id,
                        'image_path' => $imagePath,
                        'width' => $meme['width'],
                        'height' => $meme['height'],
                        'is_active' => true,
                        'is_featured' => $imported < 12, // First 12 are featured
                        'download_count' => 0,
                        'view_count' => 0,
                    ]);

                    // Attach tags
                    if (!empty($tags)) {
                        $template->tags()->attach($tags);
                    }

                    $imported++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $failed++;
                    $progressBar->advance();
                    $this->newLine();
                    $this->error("Failed to import: {$meme['name']} - " . $e->getMessage());
                }
            }

            $progressBar->finish();
            $this->newLine(2);

            // Display results
            $this->info('✅ Import Complete!');
            $this->newLine();
            $this->table(
                ['Status', 'Count'],
                [
                    ['✅ Successfully Imported', $imported],
                    ['⏭️  Skipped (Already Exists)', $skipped],
                    ['❌ Failed', $failed],
                    ['📊 Total Processed', $imported + $skipped + $failed],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Determine category from meme name and characteristics
     */
    private function getCategoryFromMeme($meme)
    {
        $name = strtolower($meme['name']);
        $boxCount = $meme['box_count'] ?? 2;

        // Category detection rules
        $categoryRules = [
            'reaction' => [
                'keywords' => ['drake', 'button', 'brain', 'guy', 'face', 'staring', 'uno', 'look', 'watching', 'pointing', 'always has been', 'waiting'],
                'icon' => '😮',
                'order' => 1
            ],
            'animals' => [
                'keywords' => ['dog', 'cat', 'monkey', 'bear', 'doge', 'seal', 'bird', 'penguin', 'fox'],
                'icon' => '🐶',
                'order' => 2
            ],
            'movies-tv' => [
                'keywords' => ['star wars', 'batman', 'thanos', 'spiderman', 'spongebob', 'anakin', 'obi-wan', 'marvel', 'picard'],
                'icon' => '🎬',
                'order' => 3
            ],
            'gaming' => [
                'keywords' => ['minecraft', 'gamer', 'gaming', 'playstation', 'xbox', 'pokemon'],
                'icon' => '🎮',
                'order' => 4
            ],
            'classic' => [
                'keywords' => ['disaster girl', 'bad luck brian', 'success kid', 'hide the pain', 'harold', 'overly', 'scumbag', 'college liberal'],
                'icon' => '📜',
                'order' => 7
            ],
            'wholesome' => [
                'keywords' => ['wholesome', 'happy', 'success', 'proud', 'love', 'friend'],
                'icon' => '💖',
                'order' => 8
            ],
        ];

        // Check each category
        foreach ($categoryRules as $slug => $rules) {
            foreach ($rules['keywords'] as $keyword) {
                if (strpos($name, $keyword) !== false) {
                    return Category::firstOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => ucwords(str_replace('-', ' & ', $slug)),
                            'icon' => $rules['icon'],
                            'order' => $rules['order']
                        ]
                    );
                }
            }
        }

        // Default category
        return Category::firstOrCreate(
            ['slug' => 'general'],
            [
                'name' => 'General',
                'icon' => '📁',
                'description' => 'General meme templates',
                'order' => 999
            ]
        );
    }

    /**
     * Generate tags from meme name
     */
    private function generateTags($memeName)
    {
        $tagIds = [];
        $name = strtolower($memeName);

        // Common tag patterns
        $tagPatterns = [
            'drake' => ['popular', 'choice', 'decision'],
            'button' => ['choice', 'decision', 'dilemma'],
            'brain' => ['smart', 'intelligence', 'thinking'],
            'distracted' => ['choice', 'temptation', 'loyalty'],
            'spongebob' => ['cartoon', 'mocking', 'funny'],
            'surprised' => ['reaction', 'shock', 'unexpected'],
            'success' => ['win', 'achievement', 'celebration'],
            'fail' => ['loss', 'mistake', 'unfortunate'],
            'change my mind' => ['debate', 'opinion', 'argument'],
            'disaster' => ['fire', 'chaos', 'destruction'],
        ];

        // Add tags based on patterns
        foreach ($tagPatterns as $pattern => $tags) {
            if (strpos($name, $pattern) !== false) {
                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
        }

        // Add generic tags
        $genericTags = ['meme', 'template', 'viral'];
        foreach ($genericTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagIds[] = $tag->id;
        }

        return array_unique($tagIds);
    }
}