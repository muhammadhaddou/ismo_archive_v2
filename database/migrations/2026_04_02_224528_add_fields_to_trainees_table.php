<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->string('cef')->nullable()->unique()->after('cin');
            $table->date('date_naissance')->nullable()->after('cef');
            $table->string('phone')->nullable()->after('date_naissance');
        });
    }

    public function down(): void
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->dropColumn(['cef', 'date_naissance', 'phone']);
        });
    }
};