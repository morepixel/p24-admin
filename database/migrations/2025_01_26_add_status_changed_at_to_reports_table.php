<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('reports')->table('reports', function (Blueprint $table) {
            $table->timestamp('status_changed_at')->nullable()->after('status');
        });

        // Initialisiere status_changed_at mit createdAt für bestehende Einträge
        DB::connection('reports')->table('reports')
            ->whereNull('status_changed_at')
            ->update(['status_changed_at' => DB::raw('createdAt')]);
    }

    public function down(): void
    {
        Schema::connection('reports')->table('reports', function (Blueprint $table) {
            $table->dropColumn('status_changed_at');
        });
    }
};
