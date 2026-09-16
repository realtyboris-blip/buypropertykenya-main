<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'currency')) {
                $table->string('currency')->default('KES')->after('price');
            }
            if (!Schema::hasColumn('properties', 'property_type_id')) {
                $table->foreignId('property_type_id')->nullable()->after('property_type');
                $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('set null');
            }
            if (!Schema::hasColumn('properties', 'listing_type_id')) {
                $table->foreignId('listing_type_id')->nullable()->after('listing_type');
                $table->foreign('listing_type_id')->references('id')->on('listing_types')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['currency', 'property_type_id', 'listing_type_id']);
        });
    }
};