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
        Schema::create('carpools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK10
            $table->foreignId('training_matches_id')->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->integer('quantity');
            $table->string('hour');
            $table->string('address');
            $table->timestamps();
        });
//        // Dummy data for Carpools
//        $userIds = DB::table('users')->pluck('id')->toArray();
//
//
//        $counter = 1;
//
//        foreach ($userIds as $userId) {
//            if ($counter < 2) {
//                DB::table('carpools')->insert([
//                    'user_id' => $userId,
//                    'training_matches_id' => rand(1, 10),
//                    'quantity' => rand(1, 5),
//                    'hour' => '10:00',
//                    'address' => 'Location ' . rand(1, 5),
//                    'created_at' => now(),
//                    'updated_at' => now(),
//                ]);
//                $counter++;
//            } else {
//                break; // Als de eerste twee gebruikers zijn ingevoegd, stop de lus.
//            }
//        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carpools');
    }
};
