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
        Schema::table('locations', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->json('map')->nullable();
            $table->string('zoom')->after('address')->nullable();
            $table->string('location_code')->nullable();
            $table->string('street_number')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('state_short')->nullable();
            $table->string('post_code')->nullable();
            $table->string('country')->nullable();
            $table->string('country_short')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('map');
            $table->dropColumn('zoom');
            $table->dropColumn('address');
            $table->dropColumn('location_code');
            $table->dropColumn('street_number');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('state_short');
            $table->dropColumn('post_code');
            $table->dropColumn('country');
            $table->dropColumn('country_short');
        });
    }
};
