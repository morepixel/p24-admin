<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('reports')->create('plz', function (Blueprint $table) {
            $table->id();
            $table->string('plz', 5);
            $table->string('city');
            $table->string('state')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::connection('reports')->dropIfExists('plz');
    }
};
