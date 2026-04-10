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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // Bac, Diplome, Attestation, etc.
            $table->enum('status', ['en_attente', 'planifie', 'termine', 'rejete'])->default('en_attente');
            $table->dateTime('appointment_date')->nullable();
            $table->text('admin_message')->nullable();
            $table->boolean('is_read_by_admin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
