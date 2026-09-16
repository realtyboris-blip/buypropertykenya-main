<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        // Insert default listing types
        DB::table('listing_types')->insert([
            ['name' => 'For Sale', 'slug' => 'sale', 'icon' => 'cash', 'sort_order' => 1, 'created_at' => now()],
            ['name' => 'For Rent', 'slug' => 'rent', 'icon' => 'calendar', 'sort_order' => 2, 'created_at' => now()],
            ['name' => 'For Lease', 'slug' => 'lease', 'icon' => 'file-document', 'sort_order' => 3, 'created_at' => now()],
            ['name' => 'Short Stay', 'slug' => 'short-stay', 'icon' => 'hotel', 'sort_order' => 4, 'created_at' => now()],
            ['name' => 'Auction', 'slug' => 'auction', 'icon' => 'gavel', 'sort_order' => 5, 'created_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_types');
    }
};