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
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->date('date')->useCurrent();
            $table->text('message');
            $table->enum('status', ['menunggu', 'proses', 'selesai'])->default('menunggu');
            $table->text('feedback')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
