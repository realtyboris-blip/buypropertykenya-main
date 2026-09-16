<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('is_featured');
            }
            if (!Schema::hasColumn('projects', 'video_url')) {
                $table->string('video_url')->nullable()->after('cover_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['cover_image', 'video_url']);
        });
    }
};