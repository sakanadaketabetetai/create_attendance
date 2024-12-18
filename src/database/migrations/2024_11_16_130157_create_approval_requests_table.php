<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->foreignId('approval_route_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained()->onDelete('cascade')->comment('承認者');
            $table->time('clock_in_time')->nullable();
            $table->time('clock_out_time')->nullable();
            $table->json('rest_times')->nullable();
            $table->date('date')->nullable();
            $table->text('late_reason')->nullable()->comment('遅刻理由');
            $table->enum('approval_status',['pending', 'approval'])->nullable()->comment('pending:保留、approval:承認');
            $table->timestamp('approval_at')->nullable();
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
        Schema::dropIfExists('approval_requests');
    }
}
