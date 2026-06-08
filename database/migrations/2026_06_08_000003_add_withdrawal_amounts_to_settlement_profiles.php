<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->decimal('withdrawal_amount', 12, 2)->default(0)->after('withdrawal_status');
            $table->decimal('total_withdrawn_amount', 12, 2)->default(0)->after('withdrawal_amount');
        });
    }

    public function down(): void
    {
        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->dropColumn(['withdrawal_amount', 'total_withdrawn_amount']);
        });
    }
};
