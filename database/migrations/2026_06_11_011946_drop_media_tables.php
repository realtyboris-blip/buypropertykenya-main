<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('media');
        Schema::dropIfExists('media_has_tags'); // If exists
    }

    public function down(): void
    {
        // Recreate media table if needed (but we won't use it)
    }
};