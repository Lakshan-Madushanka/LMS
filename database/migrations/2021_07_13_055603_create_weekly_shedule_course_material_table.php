<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklySheduleCourseMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_shedule_course_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_schedule_id')->constrained()
                ->cascadeOnUpdate();
            $table->foreignId('course_material_id')->constrained()
                ->cascadeOnUpdate();
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
        Schema::dropIfExists('weekly_shedule_course_material');
    }
}
