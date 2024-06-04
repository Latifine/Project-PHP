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
        Schema::create('clothing_per_player', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK2
            $table->foreignId("clothing_size_id")->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK19
            $table->timestamps();
        });
//        // Dummy data for Clothing Per Player
//        $userIds = DB::table('users')->pluck('id')->toArray();
//        $clothingSizeIds = DB::table('clothing_sizes')->pluck('id')->toArray();
//
//        foreach ($userIds as $userId) {
//            foreach ($clothingSizeIds as $clothingSizeId) {
//                DB::table('clothing_per_player')->insert([
//                    'user_id' => $userId,
//                    'clothing_size_id' => $clothingSizeId,
//                    'created_at' => now(),
//                    'updated_at' => now(),
//                ]);
//            }
//        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothing_per_players');
    }
};
