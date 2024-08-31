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
            $table->string('description_photo')->nullable(); 
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('start_time')->nullable(); // Allows null if no default value is needed
            $table->string('end_time')->nullable();   // Allows null if no default value is needed
            $table->string('photo')->nullable(); // Adding photo column for storing file path
            $table->string('color')->nullable(); // Adding photo column for storing file path
            $table->integer('position')->nullable(); // Making the position column nullable
            $table->boolean('completed')->nullable(); // Adding the completed column
            $table->softDeletes(); // Soft delete column
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
