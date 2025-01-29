<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('reports')->create('report_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->integer('old_status')->nullable();
            $table->integer('new_status');
            $table->string('changed_by')->nullable(); // User who made the change
            $table->text('comment')->nullable();
            $table->timestamps();

            // Fremdschlüssel ohne Constraint, da die reports-Tabelle in einer anderen Verbindung ist
            $table->index('report_id');
        });
    }

    public function down(): void
    {
        Schema::connection('reports')->dropIfExists('report_status_history');
    }
};
