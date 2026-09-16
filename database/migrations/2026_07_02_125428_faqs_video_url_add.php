<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('answer');
            $table->string('video_title')->nullable()->after('video_url');
            $table->enum('video_type', ['youtube', 'vimeo', 'local'])->nullable()->after('video_title');
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_title', 'video_type']);
        });
    }
};