<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konten', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->text('isi');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->dateTime('tanggal_publikasi')->nullable();
            $table->string('pembuat', 50);
            $table->timestamps(); // created_at & updated_at otomatis
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten');
    }
};