<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_schedules', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('month');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('payable');
            $table->integer('fees');
            $table->enum('status', ['upcomming', 'ongoing', 'withheld'])
                ->default('upcomming');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('monthly_schedule');
    }
}
