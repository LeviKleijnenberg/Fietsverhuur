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
        Schema::table('assistance_request_photos', function (Blueprint $table) {
            $table->unsignedBigInteger('assistance_request_id')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistance_request_photos', function (Blueprint $table) {
            $table->dropColumn('assistance_request_id');
        });
    }
};
