<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLocationNameToCompanyIdInLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            // Rename the 'location_name' column to 'company_id'
            $table->renameColumn('location_name', 'company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse the renaming process, changing 'company_id' back to 'location_name'
        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('company_id', 'location_name');
        });
    }
}
