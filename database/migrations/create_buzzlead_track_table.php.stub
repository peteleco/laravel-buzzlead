<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMediaTable
 */
class CreateBuzzleadTrackTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('voucher')->nullable();
        });

        Schema::create('buzzlead_trackers', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->string('voucher');
            $table->index('voucher');
            $table->boolean('dispatched');
            $table->boolean('confirmed');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('voucher');
        });

        Schema::dropIfExists('buzzlead_trackers');
    }
}