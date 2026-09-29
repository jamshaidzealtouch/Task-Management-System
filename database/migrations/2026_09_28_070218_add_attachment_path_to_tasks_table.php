<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            // Store the uploaded file path for the task.
            // The actual file will be stored in storage/app/public.
            $table->string('attachment_path')
                ->nullable()
                ->after('description');
        });
    }
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            // Remove the attachment path column if migration is rolled back.
            $table->dropColumn('attachment_path');
        });
    }
};