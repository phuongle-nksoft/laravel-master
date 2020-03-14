<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::dropIfExists('users');
        }
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique();
            $table->unsignedBigInteger('role_id')->index('users_role_id_index');
            $table->string('birthday')->nullable();
            $table->string('area')->nullable()->default('mn');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('role_id', 'users_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropIndex('users_role_id_index');
        });
        Schema::dropIfExists('users');
    }
}
