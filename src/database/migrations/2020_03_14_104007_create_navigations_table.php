<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('link')->nullable()->default('#');
            $table->integer('order_by')->nullable()->default(100);
            $table->boolean('is_active');
            $table->string('roles_id')->nullable()->default(json_encode([1, 2, 3]));
            $table->string('icon')->nullable();
            $table->longText('child')->nullable();
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
        Schema::dropIfExists('navigations');
    }
}
