<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->string('withdrawal_status')->nullable()->after('verification_status');
            $table->timestamp('withdrawal_requested_at')->nullable()->after('withdrawal_status');
            $table->timestamp('withdrawal_completed_at')->nullable()->after('withdrawal_requested_at');
        });
    }

    public function down(): void
    {
        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->dropColumn(['withdrawal_status', 'withdrawal_requested_at', 'withdrawal_completed_at']);
        });
    }
};
