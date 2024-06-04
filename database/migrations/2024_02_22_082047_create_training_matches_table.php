<?php

use Carbon\Carbon;
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
        Schema::create('training_matches', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->string('address')->nullable();
            $table->boolean('home')->default(false);
//            $table->foreignId('clothes_wash')->nullable()->constrained('training_matches')->onDelete('set null')->onUpdate('set null'); //FK14
            $table->boolean('is_match')->default(false);
            $table->string('preparation')->nullable();
            $table->string('opponent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_matches');
    }
};
