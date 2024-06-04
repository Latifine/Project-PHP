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
        Schema::create('clothing_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clothing_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); //FK18
            $table->foreignId("size_id")->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK20
            $table->timestamps();
        });
        // Dummy data for Clothing Sizes
        $clothingIds = DB::table('clothing')->pluck('id')->toArray();
        $sizeIds = DB::table('sizes')->pluck('id')->toArray();

        foreach ($clothingIds as $clothingId) {
            foreach ($sizeIds as $sizeId) {
                DB::table('clothing_sizes')->insert([
                    'clothing_id' => $clothingId,
                    'size_id' => $sizeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothing_sizes');
    }
};
