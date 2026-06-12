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
       Schema::create('local_roles', function (Blueprint $table) {
       $table->id();
       $table->string('email')->unique();
       $table->string('name')->nullable();
       $table->string('role')->default('tenant');
       $table->json('sso_payload')->nullable();
       $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_roles');
    }
};
