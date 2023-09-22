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
        Schema::create('homilies', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('citation');
            $table->string('title');
            $table->string('reading');
            $table->longText('gospel');
            $table->string('img');
            $table->string('audio');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homilies');
    }
};
