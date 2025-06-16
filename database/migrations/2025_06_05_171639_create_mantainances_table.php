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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            // Device relationship
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            // Maintenance details
            $table->string('maintenance_type')->comment('preventive, corrective, predictive, emergency');
            $table->string('title');
            $table->text('description');
            $table->text('diagnosis')->nullable();
            $table->text('solution')->nullable();

            // Status tracking
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');

            // Dates
            $table->dateTime('scheduled_date');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('completion_date')->nullable();
            $table->integer('downtime_minutes')->nullable()->comment('Total device downtime');
            // Personnel
            $table->foreignId('assigned_technician_id')->constrained('users');
            $table->foreignId('reported_by_id')->constrained('users');
            // Documentation
            $table->string('attachment_path')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Indexes for performance
            $table->index('device_id');
            $table->index('status');
            $table->index('priority');
            $table->index('scheduled_date');
            $table->index('assigned_technician_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('maintenances');
    }
};
