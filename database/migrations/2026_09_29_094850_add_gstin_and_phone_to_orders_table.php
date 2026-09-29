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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('gstin')->nullable()->after('currency');
            $table->string('phone')->nullable()->after('gstin');
        });

        Schema::table('validation_failures', function (Blueprint $table) {
            $table->boolean('is_suspicious')->default(false)->after('user_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['gstin', 'phone']);
        });

        Schema::table('validation_failures', function (Blueprint $table) {
            $table->dropColumn(['is_suspicious']);
        });
    }
};
