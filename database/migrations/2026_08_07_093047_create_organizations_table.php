<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // organization migartion file 
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')
            ->constrained('users')
            ->onDelete('cascade');
            
             $table->string('name');
             $table->text('description')->nullable();
             $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
