<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_tours', function (Blueprint $table) {
            // Make video_url nullable (it already exists)
            if (Schema::hasColumn('house_tours', 'video_url')) {
                $table->string('video_url')->nullable()->change();
            }
            
            // Add property_id if not exists (it should already exist)
            if (!Schema::hasColumn('house_tours', 'property_id')) {
                $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('house_tours', function (Blueprint $table) {
            // Revert changes if needed
        });
    }
};