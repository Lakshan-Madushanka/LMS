<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('n_i_c', 12)->unique()->nullable();
            $table->string('user_id', 10)->unique();
            $table->string('full_name', 75);
            $table->string('address', 150)->nullable();
            $table->string('nearest_town', 50)->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('contact_no', 20)->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
