<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'property_type')) {
                $table->dropColumn('property_type');
            }
            
            if (Schema::hasColumn('properties', 'listing_type')) {
                $table->dropColumn('listing_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('property_type')->nullable();
            $table->string('listing_type')->nullable();
        });
    }
};