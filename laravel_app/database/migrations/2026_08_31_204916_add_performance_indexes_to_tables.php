<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('nim');
            $table->index('is_active');
            $table->index('last_seen_at');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->index('status');
            $table->index('type');
            $table->index('date');
            $table->index('user_id');
            $table->index('class_room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['date']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['class_room_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['nim']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['last_seen_at']);
        });
    }
};
