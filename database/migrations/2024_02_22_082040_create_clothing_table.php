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
        Schema::create('clothing', function (Blueprint $table) {
            $table->id();
            $table->string('clothing');
            $table->timestamps();
        });
        // Dummy data for Clothing
        DB::table('clothing')->insert([
            ['clothing' => 'T-shirt', 'created_at' => now(), 'updated_at' => now()],
            ['clothing' => 'Short', 'created_at' => now(), 'updated_at' => now()],
            ['clothing' => 'Lange broek', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothing');
    }
};
