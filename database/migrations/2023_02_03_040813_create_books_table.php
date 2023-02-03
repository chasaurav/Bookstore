<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('author_id')->nullable()->constrained('author')->onDelete('set null');
            $table->foreignId('genre_id')->nullable()->constrained('genre')->onDelete('set null');
            $table->text('description')->nullable();
            $table->string('isbn');
            $table->string('image');
            $table->date('published')->nullable();
            $table->foreignId('publisher_id')->nullable()->constrained('publisher')->onDelete('set null');
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
        Schema::dropIfExists('books');
    }
};
