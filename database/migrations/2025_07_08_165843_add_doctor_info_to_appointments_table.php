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
    Schema::table('appointments', function (Blueprint $table) {
        $table->string('DoctorName')->nullable();
        $table->string('DoctorSpecialist')->nullable();
        $table->string('DoctorWhatsapp')->nullable();
    });
}

public function down(): void
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn(['DoctorName', 'DoctorSpecialist', 'DoctorWhatsapp']);
    });
}

};
