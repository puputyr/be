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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->enum('role', ['admin', 'psikolog', 'tim medis', 'tim keamanan']);
            $table->timestamps();
            $table->rememberToken();
        });
        
    }
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
