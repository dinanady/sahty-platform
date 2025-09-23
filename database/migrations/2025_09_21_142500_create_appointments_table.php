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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ولي الأمر
            $table->string('child_name');
            $table->date('child_birth_date');
            $table->unsignedBigInteger('vaccine_id');
            $table->unsignedBigInteger('health_center_id');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['مجدول', 'مكتمل', 'ملغي', 'لم يحضر'])->default('مجدول');
            $table->integer('dose_number')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
            $table->foreign('health_center_id')->references('id')->on('health_centers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
