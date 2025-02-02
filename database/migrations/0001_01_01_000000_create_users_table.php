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
        Schema::connection('users')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->string('role')->nullable();
            $table->string('password');
            $table->string('language')->nullable();
            $table->string('emailCode')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->string('street')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('companyName')->nullable();
            $table->string('companyForm')->nullable();
            $table->string('companyOfficer')->nullable();
            $table->string('companyNumber')->nullable();
            $table->string('companyCourt')->nullable();
            $table->bigInteger('taxInfo')->nullable();
            $table->string('birthdate')->nullable();
            $table->dateTime('lastActivity')->nullable();
            $table->smallInteger('emailReportUpdates')->default(1);
            $table->smallInteger('emailAddressUpdates')->default(1);
            $table->string('phone')->nullable();
            $table->smallInteger('questionActive')->default(0);
            $table->string('question', 2000)->default('');
            $table->string('answer', 2000)->default('');
            $table->dateTime('welcomeNsl')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('users')->create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::connection('users')->create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('users')->dropIfExists('users');
        Schema::connection('users')->dropIfExists('password_reset_tokens');
        Schema::connection('users')->dropIfExists('sessions');
    }
};
