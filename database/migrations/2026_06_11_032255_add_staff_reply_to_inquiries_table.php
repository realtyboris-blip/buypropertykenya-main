<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'staff_reply')) {
                $table->text('staff_reply')->nullable()->after('message');
            }
            if (!Schema::hasColumn('inquiries', 'replied_at')) {
                $table->timestamp('replied_at')->nullable()->after('staff_reply');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['staff_reply', 'replied_at']);
        });
    }
};