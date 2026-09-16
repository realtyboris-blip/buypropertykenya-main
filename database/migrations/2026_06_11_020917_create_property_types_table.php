<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        // Insert default property types
        DB::table('property_types')->insert([
            ['name' => 'Apartment', 'slug' => 'apartment', 'icon' => 'building-apartment', 'sort_order' => 1, 'created_at' => now()],
            ['name' => 'Villa', 'slug' => 'villa', 'icon' => 'home', 'sort_order' => 2, 'created_at' => now()],
            ['name' => 'Townhouse', 'slug' => 'townhouse', 'icon' => 'building', 'sort_order' => 3, 'created_at' => now()],
            ['name' => 'Commercial', 'slug' => 'commercial', 'icon' => 'office-building', 'sort_order' => 4, 'created_at' => now()],
            ['name' => 'Land', 'slug' => 'land', 'icon' => 'grass', 'sort_order' => 5, 'created_at' => now()],
            ['name' => 'Farm', 'slug' => 'farm', 'icon' => 'farm', 'sort_order' => 6, 'created_at' => now()],
            ['name' => 'Industrial', 'slug' => 'industrial', 'icon' => 'factory', 'sort_order' => 7, 'created_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('property_types');
    }
};