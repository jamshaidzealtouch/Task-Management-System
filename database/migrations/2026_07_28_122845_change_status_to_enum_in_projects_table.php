<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        DB::statement("
    ALTER TABLE projects
    MODIFY COLUMN status ENUM(
        'Pending',
        'In Progress',
        'Completed',
        'Cancelled'
    ) NOT NULL
");
    }


    public function down(): void
    {
        DB::statement("
            ALTER TABLE projects
            MODIFY COLUMN status VARCHAR(255) NOT NULL
        ");
    }
};