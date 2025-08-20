<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_wifis_accounts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_wifi_id')->forign('users_wifis');
            $table->string('mac')->unique();
            $table->string('name')->index()->nullable();
            $table->string('ip')->unique()->nullable();
            $table->string('leases_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_wifis_accounts');
    }
};
