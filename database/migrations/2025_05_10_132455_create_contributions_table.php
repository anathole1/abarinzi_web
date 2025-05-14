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
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The member who made the contribution
            $table->string('type'); // e.g., 'regular', 'social', 
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable(); // e.g., 'momo', 'bank_transfer', 'cash'
            $table->string('transaction_id')->nullable()->unique(); // For tracking payments
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who approved/rejected
            $table->timestamp('payment_date')->nullable(); // When the payment was actually made/confirmed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};