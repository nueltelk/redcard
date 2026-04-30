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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->date('loan_date');
            $table->date('due_date'); // max 5 hari
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('pickup_location_id')->constrained('locations');
            $table->foreignId('return_location_id')->nullable()->constrained('locations');

            $table->dateTime('return_date')->nullable();
            $table->integer('fine')->default(0);
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');

            $table->text('review')->nullable();
            $table->string('condition')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
