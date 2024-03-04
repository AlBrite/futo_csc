<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('score');
            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedBigInteger('course_id');
            $table->string('reg_no', 11);
            $table->unsignedTinyInteger('grading_id')->default(1);
            $table->integer('level');
            $table->smallInteger('lab');
            $table->smallInteger('exam');
            $table->smallInteger('test');
            $table->enum('remark', ['FAILED', 'PASSED'])->nullable();
            $table->enum('status', ['PENDING', 'APPROVED', 'COMPLETED'])->default('PENDING');
            $table->char('grade', 1);
            $table->string('session', 9);
            $table->enum('semester', ['rain', 'harmattan']);
            $table->boolean('approved')->default(false);
            $table->foreign('course_id')->references('id')->on('courses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
