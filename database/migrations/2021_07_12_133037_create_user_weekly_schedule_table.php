<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWeeklyScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_weekly_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('w_s_id');
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('w_s_id')->references('id')->on('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_weekly_schedule');
    }
}
