<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_tag', function (Blueprint $table) {
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['template_id', 'tag_id']);
            $table->timestamps(); // Optional but recommended
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_tag');
    }
};