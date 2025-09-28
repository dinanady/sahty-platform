<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('health_center_id')->nullable()->constrained()->onDelete('set null')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إعادة حقل role إلى حالته الأصلية
            $table->enum('role', ['parent', 'patient', 'admin', 'doctor'])->default('patient')->change();
            // إسقاط عمود health_center_id إذا كان موجودًا
            if (Schema::hasColumn('users', 'health_center_id')) {
                $table->dropForeign(['health_center_id']);
                $table->dropColumn('health_center_id');
            }
        });
    }
};
