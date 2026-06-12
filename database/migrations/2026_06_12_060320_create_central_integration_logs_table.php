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
         Schema::create('central_integration_logs', function (Blueprint $table) {
         $table->id();
         $table->string('activity_name');
         $table->string('contract_id')->nullable();
         $table->string('receipt_number')->nullable();
         $table->string('publish_status')->nullable();
         $table->json('payload')->nullable();
         $table->text('response_body')->nullable();
         $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_integration_logs');
    }
};
