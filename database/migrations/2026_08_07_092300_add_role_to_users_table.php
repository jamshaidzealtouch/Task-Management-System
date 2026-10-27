<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->enum('role', [

                UserRole::Admin->value,

                UserRole::Manager->value,
                UserRole::Employee->value,

            ])->default(UserRole::Employee->value);

        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('role');

        });
    }
};