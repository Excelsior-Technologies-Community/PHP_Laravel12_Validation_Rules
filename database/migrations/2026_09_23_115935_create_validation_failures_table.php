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
        Schema::create('validation_failures', function (Blueprint $table) {
            $table->id();

            $table->string('form_type')->default('order');

            $table->json('failed_fields');

            $table->json('errors');

            $table->json('input_data')->nullable();

            $table->string('ip_address')->nullable();

            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_failures');
    }
};