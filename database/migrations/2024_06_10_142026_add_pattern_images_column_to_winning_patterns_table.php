<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pattern_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('winning_pattern_id')->constrained('winning_patterns')->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pattern_images');
    }
};
