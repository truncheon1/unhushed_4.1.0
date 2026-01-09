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
        Schema::create('dict_terms', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->string('phonetic');
            $table->string('audio')->nullable();
            $table->string('slug');
            $table->string('keywords')->nullable();
            $table->string('alt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('dict_terms');
    }
};
