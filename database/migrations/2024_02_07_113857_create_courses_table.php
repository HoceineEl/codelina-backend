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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->text('image')->nullable();
            $table->text('intro')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->default(0);
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->boolean('premium')->default(false);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};