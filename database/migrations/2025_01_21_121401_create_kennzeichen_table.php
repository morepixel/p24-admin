<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('reports')->create('kennzeichen', function (Blueprint $table) {
            $table->id();
            $table->string('platecode1');
            $table->string('platecode2');
            $table->string('platecode3');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::connection('reports')->dropIfExists('kennzeichen');
    }
};
