<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('access_tokens', function (Blueprint $table): void {
            $table->decimal('price', 12, 2)->nullable()->after('ownership_tier');
            $table->string('price_currency', 3)->default('USD')->after('price');
            $table->string('btc_wallet_address')->nullable()->after('price_currency');
        });
    }

    public function down(): void
    {
        Schema::table('access_tokens', function (Blueprint $table): void {
            $table->dropColumn(['price', 'price_currency', 'btc_wallet_address']);
        });
    }
};
