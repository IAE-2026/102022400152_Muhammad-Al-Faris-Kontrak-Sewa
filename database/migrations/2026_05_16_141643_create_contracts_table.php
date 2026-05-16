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
Schema::create('contracts', function (Blueprint $table) {
    $table->id();

    $table->string('contract_number')->unique();
    $table->string('property_id');
    $table->string('tenant_id');

    $table->enum('status', [
        'draft',
        'sent',
        'signed',
        'active',
        'cancelled',
        'expired'
    ])->default('draft');

    $table->date('start_date');
    $table->date('end_date');

    $table->decimal('monthly_rent', 12, 2);
    $table->decimal('deposit_amount', 12, 2);

    $table->text('terms_and_conditions');

    $table->timestamp('signed_at')->nullable();
    $table->text('signature_data')->nullable();

    $table->string('created_by');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
