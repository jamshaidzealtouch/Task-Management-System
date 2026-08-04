<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            $table->enum('status', [
                'to_do',
                'in_progress',
                'completed',
            ])->default('to_do')->change();

            $table->enum('priority', [
                'low',
                'medium',
                'high',
            ])->default('medium')->change();

        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            $table->string('status')->change();

            $table->string('priority')->change();

        });
    }
};