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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign Key (references users.id)
            $table->date('date'); // Date
            $table->time('check_in')->nullable(); // Time (jam absen datang)
            $table->time('check_out')->nullable(); // Time (jam absen pulang)
            $table->enum('status', ['masuk', 'izin', 'sakit']); // Enum ('masuk', 'izin', 'sakit')
            $table->text('description')->nullable(); // Text (keterangan untuk izin atau sakit)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
