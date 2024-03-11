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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 25);
            $table->longText('outline')->nullable();
            $table->unsignedTinyInteger('mandatory')->default(1);
            $table->unsignedMediumInteger('grouping_id');
            $table->enum('semester', ['rain', 'harmattan']);

            // Course are not removed from the database completely, 
            // instead the column 'status' is set to 'inactive'
            $table->enum('status', ['active', 'inactive'])->default('active');


            $table->string('level', 3);
            $table->unsignedTinyInteger('exam')->default(0);
            $table->unsignedTinyInteger('test')->default(0);
            $table->unsignedTinyInteger('practical')->default(0);
            $table->unsignedTinyInteger('units');
            $table->integer('prerequisite')->default(0);
            $table->string('image')->nullable();

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
