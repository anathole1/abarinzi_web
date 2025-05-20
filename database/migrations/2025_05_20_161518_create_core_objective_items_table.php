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
        Schema::create('core_objective_items', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('about_content_id')->constrained()->onDelete('cascade'); // Technically only one about_content
            $table->string('title');
            $table->text('content');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_objective_items');
    }
};