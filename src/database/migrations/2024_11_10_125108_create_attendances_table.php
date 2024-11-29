<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->time('clock_in_time')->nullable();
            $table->time('clock_out_time')->nullable();
            $table->time('work_start_time')->nullable();
            $table->time('work_end_time')->nullable();
            $table->enum('attendance_status', ['出勤中', '休憩中', '退勤済'])->nullable();
            $table->text('late_reason')->nullable()->comment('遅刻理由');
            $table->enum('approval_status',['pending', 'approval'])->nullable()->comment('pending:保留、approval:承認');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
