<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (!Schema::hasColumn('agents', 'leads_assigned_count')) {
                $table->integer('leads_assigned_count')->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('agents', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('leads_assigned_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['leads_assigned_count', 'is_active']);
        });
    }
};