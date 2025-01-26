<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_deadlines', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->integer('days');
            $table->timestamps();
        });

        // Standard-Fristen einfügen
        DB::table('report_deadlines')->insert([
            ['status' => 'holder_inquiry_sent', 'days' => 14],
            ['status' => 'warning_sent', 'days' => 14],
            ['status' => 'invoice_sent', 'days' => 21],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('report_deadlines');
    }
};
