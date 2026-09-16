<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_types', function (Blueprint $table) {
            if (!Schema::hasColumn('listing_types', 'icon_svg')) {
                $table->text('icon_svg')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('listing_types', 'color')) {
                $table->string('color')->default('gray')->after('icon_svg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('listing_types', function (Blueprint $table) {
            $table->dropColumn(['icon_svg', 'color']);
        });
    }
};