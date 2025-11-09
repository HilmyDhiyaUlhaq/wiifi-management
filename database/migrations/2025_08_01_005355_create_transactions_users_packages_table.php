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
        Schema::create('transactions_users_packages', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->forign('users')->references('id');
            $table->string('package_id')->forign('packages')->references('id');
            $table->string('user_name')->index();
            $table->string('package_name')->index();
            $table->string('description')->nullable();
            $table->bigInteger('price');
            $table->string('status')->index();
            $table->integer('quota');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_users_packages');
    }
};
