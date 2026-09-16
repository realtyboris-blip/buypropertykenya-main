<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('contact_method')->default('phone');
            $table->string('inquiry_type');
            $table->string('budget')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable();
            $table->timestamps();
            
            $table->index('email');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};