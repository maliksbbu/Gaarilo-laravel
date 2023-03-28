<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowroomReviewRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('showroom_review_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('showroom_review_id')->unsigned();
            $table->string('rating_type')->nullable();
            $table->integer('rating_value')->nullable();
            $table->foreign('showroom_review_id')->on('showroom_reviews')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('showroom_review_ratings');
    }
}
