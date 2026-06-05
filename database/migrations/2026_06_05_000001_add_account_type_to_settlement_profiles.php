<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->string('account_type')->nullable()->after('routing_number');
        });
    }

    public function down(): void
    {
        Schema::table('settlement_profiles', function (Blueprint $table): void {
            $table->dropColumn('account_type');
        });
    }
};
