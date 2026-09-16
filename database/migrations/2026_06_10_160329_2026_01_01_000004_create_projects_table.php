<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('developer');
            $table->decimal('starting_price', 12, 2);
            $table->decimal('max_price', 12, 2)->nullable();
            $table->date('completion_date')->nullable();
            $table->string('location');
            $table->string('city');
            $table->json('floor_plans')->nullable();
            $table->json('amenities')->nullable();
            $table->json('gallery')->nullable();
            $table->string('status')->default('ongoing');
            $table->integer('total_units')->nullable();
            $table->integer('available_units')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index(['city', 'status']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};