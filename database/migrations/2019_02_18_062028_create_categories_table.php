<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_class')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign('parent_id');
        });

        Schema::drop('categories');
    }
}
