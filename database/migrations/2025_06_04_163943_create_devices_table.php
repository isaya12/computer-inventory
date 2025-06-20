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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('serial_number')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->date('purchase_date');
            $table->enum('status', ['available', 'assigned', 'maintenance','borrow','out_of_service'])
                  ->default('available');
            $table->string('barcode')->nullable()->unique();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('location_id')->nullable()->constrained('locations');
            $table->softDeletes();
            $table->timestamps();

            // Indexes for better performance
            $table->index('serial_number');
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
