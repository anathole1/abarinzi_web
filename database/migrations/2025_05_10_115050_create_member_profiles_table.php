<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
             $table->string('accountNo')->unique();
            $table->string('phone_number')->unique();
            $table->string('email')->unique();
            $table->string('national_id')->unique();
            $table->date('dateJoined')->nullable();
            $table->string('photoUrl')->nullable();
            $table->string('year_left_efotec')->nullable();
            $table->string('current_location')->nullable();

            // OLD: $table->enum('membership_category', ['bronze', 'silver', 'gold']);
            // NEW:
            $table->foreignId('member_category_id')->nullable()->constrained('member_categories')->onDelete('restrict'); // Or onDelete('set null') if category can be deleted

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('member_profiles');
    }
};