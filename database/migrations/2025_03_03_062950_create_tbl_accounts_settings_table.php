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
        Schema::create('tbl_accounts_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_account_id')->nullable();
            $table->foreignId('masrofat_account_id')->nullable();
            $table->foreignId('employee_account_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_accounts_settings');
    }
};
