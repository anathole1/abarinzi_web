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
        Schema::table('member_profiles', function (Blueprint $table) {
             $table->foreignId('member_category_id')->nullable()->constrained('member_categories')->onDelete('cascade'); // Or onDelete('set null') if category can be deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            //
        });
    }
};
