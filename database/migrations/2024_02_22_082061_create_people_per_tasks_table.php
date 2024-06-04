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
        Schema::create('people_per_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK7
            $table->foreignId('task_per_activity_id')->references('id')->on('tasks_per_activity')->onDelete('cascade')->onUpdate('cascade'); //FK12
            $table->string('reason_exceptional_circumstance')->nullable();
            $table->boolean('is_assigned')->default(false);
            $table->timestamps();
        });
//        // Dummy data for Person Per Tasks
//        $userIds = DB::table('users')->pluck('id')->toArray();
//        $taskPerActivityIds = DB::table('tasks_per_activity')->pluck('id')->toArray();
//
//
//                DB::table('people_per_task')->insert([
//                    ['user_id' => 1,
//                    'task_per_activity_id' => 1,
//                    'reason_exceptional_circumstance' => null,
//                    'is_assigned' => rand(0, 1),
//                    'created_at' => now(),
//                    'updated_at' => now()],
//                    ['user_id' => 1,
//                        'task_per_activity_id' => 3,
//                        'reason_exceptional_circumstance' => null,
//                        'is_assigned' => rand(0, 1),
//                        'created_at' => now(),
//                        'updated_at' => now()],
//
//                ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_per_tasks');
    }
};
