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
        Schema::connection('reports')->create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('companyname', 128)->default('');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('platecode1');
            $table->string('platecode2');
            $table->string('platecode3');
            $table->dateTime('date');
            $table->bigInteger('uploadstatus');
            $table->bigInteger('status');
            $table->bigInteger('sentstatus');
            $table->bigInteger('alreadyinsystem')->default(0);
            $table->string('city');
            $table->string('zip');
            $table->string('street');
            $table->string('country');
            $table->double('lat');
            $table->double('lng');
            $table->bigInteger('order')->default(0);
            $table->bigInteger('userid')->nullable();
            $table->bigInteger('addressid')->default(0);
            $table->string('lawyerdetails', 2000)->nullable();
            $table->string('halterdatum', 128)->nullable();
            $table->string('haltername', 128)->nullable();
            $table->string('zahlungsziel', 128)->nullable();
            $table->string('kennnummer', 128)->nullable();
            $table->string('halterplz', 128)->nullable();
            $table->string('halterort', 128)->nullable();
            $table->string('halterstrasse')->nullable();
            $table->string('kbafile')->nullable();
            $table->boolean('paymentstatus')->default(false);
            $table->boolean('adminemailsent')->default(false);
            $table->string('fahrername')->nullable();
            $table->string('fahrerort')->nullable();
            $table->string('fahrerplz')->nullable();
            $table->string('fahrerstrasse')->nullable();
            $table->string('fahrergeschlecht')->nullable();
            $table->string('haltergeschlecht')->nullable();
            $table->string('mandantgeschlecht')->nullable();
            $table->text('notes')->nullable();
            $table->string('uefile')->nullable();
            $table->timestamp('uefileuploadedat')->nullable();
            $table->text('reportresponse')->nullable();
            $table->boolean('paidkba')->default(false);
            $table->integer('lawyerapprovalstatus')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('reports')->dropIfExists('reports');
    }
};
