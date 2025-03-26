<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistance_requests', function (Blueprint $table) {
            $table->id();  // auto-incrementing primary key
            $table->string('latlong')->nullable();  // Latlong field (string)
            $table->text('description')->nullable();  // description field (text)
            $table->text('photos')->nullable();  // photos field (json)
            $table->unsignedBigInteger('bike_id')->nullable();  // bike_id field (foreign key reference)
            $table->timestamps();  // created_at and updated_at

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assistance_requests');
    }
}
