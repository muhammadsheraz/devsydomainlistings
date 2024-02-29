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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->year('exists_since');
            $table->timestamp('starting_date');
            $table->timestamp('ending_date');
            $table->decimal('target_price', 20, 2)->nullable();
            $table->decimal('min_bid_increment', 10, 2)->nullable();
            $table->decimal('starting_price', 10, 2)->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
