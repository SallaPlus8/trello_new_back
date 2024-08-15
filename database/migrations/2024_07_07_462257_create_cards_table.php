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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('the_list_id')->constrained('the_lists')->cascadeOnDelete();
            $table->text('text');
            $table->text('description')->nullable();
            $table->timestamp('start_time')->nullable(); // Allows null if no default value is needed
            $table->timestamp('end_time')->nullable();   // Allows null if no default value is needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
