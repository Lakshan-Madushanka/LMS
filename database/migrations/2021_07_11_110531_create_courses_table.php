<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->unique();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->boolean('payable')->default(true);
            $table->integer('fees')->default(0);
            $table->enum('status', ['upcomming', 'ongoing', 'withheld'])
                ->default('upcomming');
            $table->text('description')->nullable();
            $table->string('image', 50)->nullable();
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
        Schema::dropIfExists('courses');
    }
}
