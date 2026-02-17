<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Template;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FetchAllMemes extends Command
{
    protected $signature = 'memes:fetch-all
                            {--source=all : Source to fetch from (all/imgflip/memegen/reddit/indian)}
                            {--limit=50   : Total number of memes to fetch}';

    protected $description = 'Fetch memes from multiple sources: Imgflip, Memegen.link, Reddit, Indian';

    private int $saved   = 0;
    private int $skipped = 0;
    private int $failed  = 0;

    public function handle(): int
    {
        $source = $this->option('source');
        $limit  = (int) $this->option('limit');

        $this->info("🚀 Fetching memes | Source: {$source} | Limit: {$limit}");
        $this->newLine();

        $perSource = (int) ceil($limit / match($source) {
            'all'    => 4,
            default  => 1,
        });

        if (in_array($source, ['all', 'imgflip'])) {
            $this->fetchFromImgflip($perSource);
        }

        if (in_array($source, ['all', 'memegen'])) {
            $this->fetchFromMemegen($perSource);
        }

        if (in_array($source, ['all', 'reddit'])) {
            $this->fetchFromReddit($perSource);
        }

        if (in_array($source, ['all', 'indian'])) {
            $this->fetchIndianMemes($perSource);
        }

        $this->newLine();
        $this->table(
            ['✅ Saved', '⏭ Skipped', '❌ Failed'],
            [[$this->saved, $this->skipped, $this->failed]]
        );

        return Command::SUCCESS;
    }

    // =========================================================
    // SOURCE 1: Imgflip — 100 popular templates (FREE, no auth)
    // API: https://api.imgflip.com/get_memes
    // =========================================================
    private function fetchFromImgflip(int $limit): void
    {
        $this->info('📦 [1/4] Imgflip API — Popular meme templates...');

        try {
            $response = Http::timeout(15)->get('https://api.imgflip.com/get_memes');

            if (!$response->successful()) {
                $this->error('  ❌ Imgflip API failed: ' . $response->status());
                return;
            }

            $memes    = $response->json('data.memes', []);
            $category = $this->getOrCreateCategory('popular', 'Popular Memes', '🔥');
            $count    = 0;

            foreach ($memes as $meme) {
                if ($count >= $limit) break;

                if ($this->saveMeme([
                    'name'     => $meme['name'],
                    'url'      => $meme['url'],
                    'width'    => $meme['width']  ?? 500,
                    'height'   => $meme['height'] ?? 500,
                    'tags'     => ['popular', 'imgflip', 'trending'],
                    'source'   => 'imgflip',
                    'featured' => $count < 10,
                ], $category)) {
                    $count++;
                }
            }

            $this->info("  ✅ Imgflip: processed " . count($memes) . " memes");

        } catch (\Exception $e) {
            $this->error('  ❌ Imgflip error: ' . $e->getMessage());
        }
    }

    // =========================================================
    // SOURCE 2: Memegen.link — 300+ templates (FREE, no auth)
    // API: https://api.memegen.link/templates/
    // =========================================================
    private function fetchFromMemegen(int $limit): void
    {
        $this->info('🎭 [2/4] Memegen.link API — Classic meme templates...');

        try {
            $response = Http::timeout(15)->get('https://api.memegen.link/templates/');

            if (!$response->successful()) {
                $this->error('  ❌ Memegen API failed: ' . $response->status());
                return;
            }

            $templates = $response->json();
            $category  = $this->getOrCreateCategory('classic', 'Classic Memes', '😂');
            $count     = 0;

            foreach ($templates as $template) {
                if ($count >= $limit) break;

                // Use the blank (no text) template image
                $imageUrl = $template['blank'] ?? ($template['example']['url'] ?? null);

                if (!$imageUrl) continue;

                // Convert to jpg for consistency
                $imageUrl = str_replace('.webp', '.jpg', $imageUrl);

                if ($this->saveMeme([
                    'name'     => $template['name'] ?? Str::title($template['id']),
                    'url'      => $imageUrl,
                    'width'    => $template['width']  ?? 500,
                    'height'   => $template['height'] ?? 500,
                    'tags'     => ['classic', 'memegen', $template['id']],
                    'source'   => 'memegen',
                    'featured' => $count < 5,
                    'meta'     => [
                        'memegen_id' => $template['id'],
                        'lines'      => $template['lines'] ?? 2,
                    ],
                ], $category)) {
                    $count++;
                }
            }

            $this->info("  ✅ Memegen: processed " . count($templates) . " templates");

        } catch (\Exception $e) {
            $this->error('  ❌ Memegen error: ' . $e->getMessage());
        }
    }

    // =========================================================
    // SOURCE 3: Reddit JSON API — Real viral memes (FREE)
    // Uses Reddit's public .json endpoint — no auth needed
    // =========================================================
    private function fetchFromReddit(int $limit): void
    {
        $this->info('🤖 [3/4] Reddit API — Viral memes from top subreddits...');

        // Indian + global meme subreddits
        $subreddits = [
            'IndianMeme'     => ['indian', 'desi', 'hindi'],
            'dankrishu'      => ['indian', 'desi', 'funny'],
            'indiameme'      => ['indian', 'relatable', 'desi'],
            'bakchodi'       => ['indian', 'bakchodi', 'desi'],
            'unitedstatesofindia' => ['indian', 'politics', 'news'],
            'dankmemes'      => ['dank', 'funny', 'viral'],
            'memes'          => ['memes', 'funny', 'popular'],
            'me_irl'         => ['relatable', 'irl', 'funny'],
            'wholesomememes' => ['wholesome', 'cute', 'positive'],
        ];

        $perSubreddit = max(1, (int) ceil($limit / count($subreddits)));
        $total        = 0;

        foreach ($subreddits as $subreddit => $defaultTags) {
            if ($total >= $limit) break;

            $fetched = $this->fetchFromSubreddit($subreddit, $defaultTags, $perSubreddit);
            $total  += $fetched;
        }

        $this->info("  ✅ Reddit: fetched {$total} memes");
    }

    private function fetchFromSubreddit(string $subreddit, array $defaultTags, int $limit): int
    {
        $count = 0;

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'MemeVault/1.0 (Laravel meme app)',
                    'Accept'     => 'application/json',
                ])
                ->get("https://www.reddit.com/r/{$subreddit}/top.json", [
                    'limit'  => min(100, $limit * 3), // fetch extra to filter
                    't'      => 'month',               // top of the month
                ]);

            if (!$response->successful()) {
                $this->warn("  ⚠️  r/{$subreddit}: HTTP " . $response->status());
                return 0;
            }

            $posts = $response->json('data.children', []);

            // Detect category based on subreddit
            $isIndian   = in_array($subreddit, ['IndianMeme', 'dankrishu', 'indiameme', 'bakchodi', 'unitedstatesofindia']);
            $categoryData = $isIndian
                ? ['slug' => 'indian-bollywood', 'name' => 'Indian / Bollywood', 'icon' => '🇮🇳']
                : ['slug' => 'reddit-memes',     'name' => 'Reddit Memes',       'icon' => '🤖'];

            $category = $this->getOrCreateCategory(
                $categoryData['slug'],
                $categoryData['name'],
                $categoryData['icon']
            );

            foreach ($posts as $post) {
                if ($count >= $limit) break;

                $data = $post['data'] ?? [];

                // ✅ Skip non-image posts, NSFW, spoilers, low quality
                if (
                    ($data['is_self']   ?? true)  ||
                    ($data['over_18']   ?? false) ||
                    ($data['spoiler']   ?? false) ||
                    ($data['ups']       ?? 0) < 100
                ) {
                    continue;
                }

                $imageUrl = $this->extractRedditImageUrl($data);
                if (!$imageUrl) continue;

                // Build tags from post title + default tags
                $tags = array_merge(
                    $defaultTags,
                    ['reddit', "r/{$subreddit}"],
                    $this->extractTagsFromTitle($data['title'] ?? '')
                );

                if ($this->saveMeme([
                    'name'     => $this->cleanTitle($data['title'] ?? 'Reddit Meme'),
                    'url'      => $imageUrl,
                    'width'    => 500,
                    'height'   => 500,
                    'tags'     => $tags,
                    'source'   => "reddit/r/{$subreddit}",
                    'featured' => ($data['ups'] ?? 0) > 10000,
                ], $category)) {
                    $count++;
                    $this->line("    📌 r/{$subreddit}: " . Str::limit($data['title'], 50));
                }
            }

        } catch (\Exception $e) {
            $this->warn("  ⚠️  r/{$subreddit} error: " . $e->getMessage());
        }

        return $count;
    }

    private function extractRedditImageUrl(array $data): ?string
    {
        $url = $data['url'] ?? '';

        // Direct image URLs
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i', $url)) {
            return $url;
        }

        // i.redd.it images
        if (str_contains($url, 'i.redd.it')) {
            return $url;
        }

        // Reddit preview images
        $preview = $data['preview']['images'][0]['source']['url'] ?? null;
        if ($preview) {
            return html_entity_decode($preview);
        }

        // imgur direct
        if (str_contains($url, 'imgur.com') && !str_contains($url, '/a/')) {
            $imgurId = basename(parse_url($url, PHP_URL_PATH));
            return "https://i.imgur.com/{$imgurId}.jpg";
        }

        return null;
    }

    private function extractTagsFromTitle(string $title): array
    {
        // Extract meaningful words as tags
        $words    = preg_split('/\s+/', strtolower($title));
        $stopwords = ['the', 'a', 'an', 'is', 'are', 'was', 'were', 'be', 'been', 'being',
                      'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could',
                      'should', 'may', 'might', 'shall', 'can', 'need', 'dare', 'ought',
                      'to', 'of', 'in', 'for', 'on', 'with', 'at', 'by', 'from', 'as',
                      'into', 'through', 'during', 'before', 'after', 'above', 'below',
                      'when', 'where', 'why', 'how', 'all', 'both', 'each', 'few', 'more',
                      'most', 'other', 'some', 'such', 'only', 'own', 'same', 'than',
                      'too', 'very', 'just', 'but', 'if', 'or', 'and', 'not', 'no', 'nor',
                      'so', 'yet', 'both', 'this', 'that', 'these', 'those', 'i', 'me',
                      'my', 'we', 'our', 'you', 'your', 'he', 'she', 'his', 'her', 'it',
                      'its', 'they', 'them', 'their', 'what', 'which', 'who', 'whom'];

        return collect($words)
            ->map(fn($w) => preg_replace('/[^a-z0-9]/', '', $w))
            ->filter(fn($w) => strlen($w) > 3 && !in_array($w, $stopwords))
            ->unique()
            ->take(5)
            ->values()
            ->toArray();
    }

    private function cleanTitle(string $title): string
    {
        // Remove emojis, trim, limit length
        $clean = preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $title);
        $clean = preg_replace('/[^\x20-\x7E\x{0900}-\x{097F}]/u', '', $clean); // keep ASCII + Devanagari
        $clean = trim($clean);
        return Str::limit($clean, 100) ?: 'Meme';
    }

    // =========================================================
    // SOURCE 4: Indian Memes — curated + D3vd Meme API
    // D3vd API: https://meme-api.com/gimme/{subreddit}/{count}
    // =========================================================
    private function fetchIndianMemes(int $limit): void
    {
        $this->info('🇮🇳 [4/4] Indian Memes — D3vd API + curated list...');

        $indianSubreddits = [
            'IndianMeme', 'dankrishu', 'indiameme',
            'bakchodi', 'SaimanSays',
        ];

        $category = $this->getOrCreateCategory('indian-bollywood', 'Indian / Bollywood', '🇮🇳');
        $count    = 0;
        $perSub   = max(1, (int) ceil($limit / count($indianSubreddits)));

        foreach ($indianSubreddits as $subreddit) {
            if ($count >= $limit) break;

            try {
                // D3vd's free meme API — no auth required
                $response = Http::timeout(15)
                    ->withHeaders(['User-Agent' => 'MemeVault/1.0'])
                    ->get("https://meme-api.com/gimme/{$subreddit}/{$perSub}");

                if (!$response->successful()) {
                    // Fallback to direct Reddit JSON
                    $this->line("  ↩️  D3vd failed for r/{$subreddit}, using Reddit JSON...");
                    $count += $this->fetchFromSubreddit(
                        $subreddit,
                        ['indian', 'desi', strtolower($subreddit)],
                        $perSub
                    );
                    continue;
                }

                $memes = $response->json('memes', []);

                foreach ($memes as $meme) {
                    if ($count >= $limit) break;
                    if ($meme['nsfw'] ?? false) continue;

                    $imageUrl = $meme['url'] ?? '';
                    if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $imageUrl)) continue;

                    if ($this->saveMeme([
                        'name'     => $this->cleanTitle($meme['title'] ?? 'Indian Meme'),
                        'url'      => $imageUrl,
                        'width'    => 500,
                        'height'   => 500,
                        'tags'     => ['indian', 'desi', strtolower($subreddit), 'reddit'],
                        'source'   => "d3vd/{$subreddit}",
                        'featured' => ($meme['ups'] ?? 0) > 5000,
                    ], $category)) {
                        $count++;
                        $this->line("    🇮🇳 " . Str::limit($meme['title'], 50));
                    }
                }

            } catch (\Exception $e) {
                $this->warn("  ⚠️  D3vd r/{$subreddit}: " . $e->getMessage());
            }
        }

        $this->info("  ✅ Indian memes: fetched {$count}");
    }

    // =========================================================
    // SHARED HELPERS
    // =========================================================
    private function saveMeme(array $meme, Category $category): bool
    {
        try {
            // Skip if name too short or already exists
            if (strlen($meme['name']) < 3) return false;

            if (Template::where('name', $meme['name'])->exists()) {
                $this->skipped++;
                return false;
            }

            // Download image
            $slug      = Str::slug($meme['name']) . '-' . strtoupper(Str::random(5));
            $imagePath = $this->downloadImage($meme['url'], $slug);

            if (!$imagePath) {
                $this->failed++;
                return false;
            }

            // Save template
            $template = Template::create([
                'name'           => $meme['name'],
                'slug'           => $slug,
                'category_id'    => $category->id,
                'image_path'     => $imagePath,
                'width'          => $meme['width']  ?? 500,
                'height'         => $meme['height'] ?? 500,
                'download_count' => rand(5, 200),
                'view_count'     => rand(50, 2000),
                'is_featured'    => $meme['featured'] ?? false,
                'is_premium'     => false,
                'is_active'      => true,
            ]);

            // Attach tags
            $this->attachTags($template, $meme['tags'] ?? []);

            $this->saved++;
            return true;

        } catch (\Exception $e) {
            $this->warn("  ❌ Save failed: " . $e->getMessage());
            $this->failed++;
            return false;
        }
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

            if ($data === false || strlen($data) < 500) return null;

            $ext      = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
            $ext      = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? $ext : 'jpg';
            $filename = 'templates/' . $slug . '.' . $ext;

            Storage::disk('public')->put($filename, $data);
            return $filename;

        } catch (\Exception $e) {
            return null;
        }
    }

    private function getOrCreateCategory(string $slug, string $name, string $icon): Category
    {
        return Category::firstOrCreate(
            ['slug' => $slug],
            [
                'name'      => $name,
                'slug'      => $slug,
                'icon'      => $icon,
                'is_active' => true,
            ]
        );
    }

    private function attachTags(Template $template, array $tags): void
    {
        foreach (array_unique($tags) as $tagName) {
            if (strlen($tagName) < 2) continue;
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName, 'slug' => Str::slug($tagName)]
            );
            $template->tags()->syncWithoutDetaching([$tag->id]);
        }
    }
}