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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('companyName', 128)->default('');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('plateCode1');
            $table->string('plateCode2');
            $table->string('plateCode3');
            $table->dateTime('date');
            $table->bigInteger('uploadStatus');
            $table->bigInteger('status');
            $table->bigInteger('sentStatus');
            $table->bigInteger('alreadyInSystem')->default(0);
            $table->string('city');
            $table->string('zip');
            $table->string('street');
            $table->string('country');
            $table->double('lat');
            $table->double('lng');
            $table->bigInteger('order')->default(0);
            $table->bigInteger('userId')->nullable();
            $table->bigInteger('addressId')->default(0);
            $table->string('lawyerDetails', 2000)->nullable();
            $table->string('halterDatum', 128)->nullable();
            $table->string('halterName', 128)->nullable();
            $table->string('zahlungsziel', 128)->nullable();
            $table->string('kennnummer', 128)->nullable();
            $table->string('halterPLZ', 128)->nullable();
            $table->string('halterOrt', 128)->nullable();
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
