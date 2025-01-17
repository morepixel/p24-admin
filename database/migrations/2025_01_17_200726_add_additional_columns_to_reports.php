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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('halterStrasse')->nullable();
            $table->string('kbaFile')->nullable();
            $table->boolean('paymentStatus')->default(false);
            $table->boolean('adminEmailSent')->default(false);
            $table->string('fahrerName')->nullable();
            $table->string('fahrerOrt')->nullable();
            $table->string('fahrerPLZ')->nullable();
            $table->string('fahrerStrasse')->nullable();
            $table->string('fahrerGeschlecht')->nullable();
            $table->string('halterGeschlecht')->nullable();
            $table->string('mandantGeschlecht')->nullable();
            $table->text('notes')->nullable();
            $table->string('ueFIle')->nullable();
            $table->timestamp('ueFileUploadedAt')->nullable();
            $table->text('reportResponse')->nullable();
            $table->boolean('paidKBA')->default(false);
            $table->integer('lawyerApprovalStatus')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'halterStrasse',
                'kbaFile',
                'paymentStatus',
                'adminEmailSent',
                'fahrerName',
                'fahrerOrt',
                'fahrerPLZ',
                'fahrerStrasse',
                'fahrerGeschlecht',
                'halterGeschlecht',
                'mandantGeschlecht',
                'notes',
                'ueFIle',
                'ueFileUploadedAt',
                'reportResponse',
                'paidKBA',
                'lawyerApprovalStatus'
            ]);
        });
    }
};
