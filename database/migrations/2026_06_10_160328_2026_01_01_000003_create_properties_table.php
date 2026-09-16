<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->string('price_period')->default('month');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area_sqft')->nullable();
            $table->string('property_type');
            $table->string('listing_type');
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('amenities')->nullable();
            $table->json('features')->nullable();
            $table->string('status')->default('available');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null');
            $table->integer('views_count')->default(0);
            $table->date('available_from')->nullable();
            $table->timestamps();
            
            $table->index(['city', 'status']);
            $table->index(['price', 'bedrooms']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};