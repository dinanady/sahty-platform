<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('scientific_name')->nullable();
            $table->text('description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->boolean('insurance_covered')->default(false);
            $table->string('category')->nullable();
            $table->string('dosage_form')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('pending');
            $table->unsignedBigInteger('submitted_by_center_id')->nullable();
            $table->boolean('is_government_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
