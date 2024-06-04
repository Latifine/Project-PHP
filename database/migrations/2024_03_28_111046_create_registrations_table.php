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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->boolean('paid')->default(false);
            $table->foreignId('season_id')->constrained('seasons')->onDelete('restrict')->onUpdate('cascade'); // FK6
            $table->foreignId("user_id")->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK6
            $table->timestamps();
            $table->unique(['season_id', 'user_id']); // Add unique constraint
        });
//        $userIds = DB::table('users')->pluck('id')->toArray();
//        $seasonId = DB::table('seasons')->pluck('id')->first();

//        foreach ($userIds as $userId) {
//            DB::table('registrations')->insert([
//                'paid' => rand(0, 1),
//                'season_id' => $seasonId,
//                'user_id' => $userId,
//                'created_at' => now(),
//                'updated_at' => now(),
//            ]);
//        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('seasons');
    }
};

