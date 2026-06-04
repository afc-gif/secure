<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_profiles', function (Blueprint $table): void {
            $table->string('cash_app_handle')->nullable()->after('postal_code');
        });

        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->string('payout_platform')->default('cash_app')->after('user_id');
            $table->string('cash_app_handle')->nullable()->after('payout_platform');
        });
    }

    public function down(): void
    {
        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->dropColumn(['payout_platform', 'cash_app_handle']);
        });

        Schema::table('member_profiles', function (Blueprint $table): void {
            $table->dropColumn('cash_app_handle');
        });
    }
};
