<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/TemplateSeeder.php
public function run()
{
    $templates = [
        [
            'name' => 'Drake Hotline Bling',
            'slug' => 'drake-hotline-bling',
            'category_id' => 1,
            'image_path' => 'templates/drake.jpg',
            'width' => 1200,
            'height' => 1200,
            'is_featured' => true,
        ],
        [
            'name' => 'Distracted Boyfriend',
            'slug' => 'distracted-boyfriend',
            'category_id' => 1,
            'image_path' => 'templates/distracted-boyfriend.jpg',
            'width' => 1200,
            'height' => 800,
            'is_featured' => true,
        ],
        // Add more templates...
    ];
    
    foreach ($templates as $template) {
        Template::create($template);
    }
}
}
