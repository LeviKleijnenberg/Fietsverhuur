<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('assistance_requests', function (Blueprint $table) {
            // Remove the existing 'photos' column
            $table->dropColumn('photos');

            // Add a new foreign key column for related photos
            $table->unsignedBigInteger('assistance_request_photos_id')->nullable()->after('id');
            $table->foreign('assistance_request_photos_id')->references('id')->on('assistance_request_photos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('assistance_requests', function (Blueprint $table) {
            // Rollback: Remove the foreign key and column
            $table->dropForeign(['assistance_request_photos_id']);
            $table->dropColumn('assistance_request_photos_id');

            // Restore the original 'photos' column
            $table->text('photos')->nullable();
        });
    }
};
