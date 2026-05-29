<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contributions')) {
            return;
        }

        Schema::table('contributions', function (Blueprint $table) {
            if (! Schema::hasColumn('contributions', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('amount');
            }

            if (! Schema::hasColumn('contributions', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->unique()->after('status');
            }

            if (! Schema::hasColumn('contributions', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('contributions', 'approved_by_admin_id')) {
                $table->foreignId('approved_by_admin_id')->nullable()->after('admin_notes')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('contributions', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by_admin_id');
            }
        });

        if (Schema::hasColumn('contributions', 'reference_code') && Schema::hasColumn('contributions', 'payment_reference')) {
            DB::table('contributions')
                ->whereNull('payment_reference')
                ->update(['payment_reference' => DB::raw('reference_code')]);
        }

        if (DB::getDriverName() === 'pgsql' && Schema::hasColumn('contributions', 'batch_id')) {
            DB::statement('ALTER TABLE contributions ALTER COLUMN batch_id DROP NOT NULL');
            DB::statement(<<<'SQL'
DO $$
DECLARE constraint_record record;
BEGIN
    FOR constraint_record IN
        SELECT conname
        FROM pg_constraint
        WHERE conrelid = 'contributions'::regclass
          AND contype = 'c'
          AND pg_get_constraintdef(oid) ~ '(contribution_type|status)'
    LOOP
        EXECUTE format('ALTER TABLE contributions DROP CONSTRAINT %I', constraint_record.conname);
    END LOOP;
END $$;
SQL);
        }
    }

    public function down(): void
    {
        //
    }
};
