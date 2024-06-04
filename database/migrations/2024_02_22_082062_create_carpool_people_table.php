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
        Schema::create('carpool_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK15
            $table->foreignId('carpool_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); //FK16
            $table->integer('quantity');
            $table->timestamps();
        });
//        // Dummy data for Carpool People
//        $userIds = DB::table('users')->pluck('id')->toArray();
//        $carpoolIds = DB::table('carpools')->pluck('id')->toArray();
//
//        foreach ($userIds as $userId) {
//            foreach ($carpoolIds as $carpoolId) {
//                DB::table('carpool_people')->insert([
//                    'user_id' => $userId,
//                    'carpool_id' => $carpoolId,
//                    'quantity' => rand(1, 3),
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
        Schema::dropIfExists('carpool_people');
    }
};
