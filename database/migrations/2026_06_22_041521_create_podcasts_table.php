<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Media Type: audio or video
            $table->enum('media_type', ['audio', 'video'])->default('audio');
            
            // Platform (YouTube, Vimeo, Spotify, Apple Podcasts, SoundCloud, etc.)
            $table->string('platform')->nullable();
            
            // Audio content (for audio podcasts)
            $table->string('audio_url')->nullable();
            
            // Video content (for video podcasts)
            $table->string('video_url')->nullable();
            $table->string('video_thumbnail')->nullable();
            
            // Embed URL for easy embedding
            $table->string('embed_url')->nullable();
            
            // Common fields
            $table->string('cover_image')->nullable();
            $table->string('duration')->nullable();
            $table->string('host')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft'); // draft, published
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'published_at']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};