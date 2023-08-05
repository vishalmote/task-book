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
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('description');
            $table->date('start_date');
            $table->date('due_date');
            $table->enum('status', ['New', 'Incomplete', 'Complete']);
            $table->enum('priority', ['High', 'Medium', 'Low']);
            $table->unsignedBigInteger('created_by_id');
            $table->timestamps();
            $table->foreign('created_by_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
