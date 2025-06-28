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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Member who applied
            $table->decimal('amount_requested', 15, 2);
            $table->decimal('amount_approved', 15, 2)->nullable();
            $table->text('purpose');
            $table->decimal('interest_rate', 5, 2)->nullable()->comment('Annual percentage rate'); // e.g., 5.00 for 5%
            $table->integer('term_months')->nullable()->comment('Loan term in months');
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'repaid', 'defaulted'])->default('pending');
            $table->date('application_date');
            $table->date('approval_date')->nullable();
            $table->date('disbursement_date')->nullable();
            $table->date('first_payment_due_date')->nullable();
            $table->date('final_due_date')->nullable(); // Calculated or set
            $table->text('admin_notes')->nullable(); // Notes by admin
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
