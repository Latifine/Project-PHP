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
        Schema::create('tasks_per_activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->references('id')->on('training_matches')->onDelete('cascade')->onUpdate('cascade'); //FK11
            $table->foreignId('task_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK13
            $table->integer('quantity');
            $table->timestamps();
        });
//        // Dummy data for Task Per Activities
//        $activityIds = DB::table('training_matches')->pluck('id')->toArray();
//        $taskIds = DB::table('tasks')->pluck('id')->toArray();
//
//
//        DB::table('tasks_per_activity')->insert([
//                    ['activity_id' => 2,
//                    'task_id' => 1,
//                    'quantity' => rand(1, 5),
//                    'created_at' => now(),
//                    'updated_at' => now()],
//                    ['activity_id' => 2,
//                    'task_id' => 2,
//                    'quantity' => rand(1, 5),
//                    'created_at' => now(),
//                    'updated_at' => now()],
//            ['activity_id' => 2,
//                'task_id' => 3,
//                'quantity' => rand(1, 5),
//                'created_at' => now(),
//                'updated_at' => now()],
//                ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_per_activities');
    }
};
