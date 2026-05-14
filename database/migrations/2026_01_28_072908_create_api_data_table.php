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
        Schema::create('api_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_id')->constrained('api_keys')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('ip_address');
            $table->string('file_path');
            $table->string('url')->unique();
            $table->string('status');
            $table->string('id_tabel')->nullable();
            $table->string('tabel_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_data');
    }
};
