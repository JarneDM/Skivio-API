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
        Schema::create('task_label', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('label_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_label');
    }
};
