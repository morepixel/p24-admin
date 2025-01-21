<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Logs table in reports database
        Schema::connection('reports')->create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('type');
            $table->bigInteger('typeid');
            $table->text('message');
            $table->bigInteger('userid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Logs table in addresses database
        Schema::connection('addresses')->create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('type');
            $table->bigInteger('typeid');
            $table->text('message');
            $table->bigInteger('userid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Logs table in users database
        Schema::connection('users')->create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('type');
            $table->bigInteger('typeid');
            $table->text('message');
            $table->bigInteger('userid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::connection('reports')->dropIfExists('logs');
        Schema::connection('addresses')->dropIfExists('logs');
        Schema::connection('users')->dropIfExists('logs');
    }
};
