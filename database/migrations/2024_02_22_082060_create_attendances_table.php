<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK8
            $table->foreignId('activity_id')->constrained('training_matches')->onDelete('cascade')->onUpdate('cascade'); //FK9
            $table->boolean('is_announced_absent')->default(false);
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        // Create attendance records for actual absences




//                // Simulate only creating records for a subset of users and training matches
//
//                    DB::table('attendances')->insert([
//                        ['user_id' => 3,
//                        'activity_id' => 5,
//                        'is_announced_absent' => 1,
//                        'reason' => 'Jefke heeft Buikgriep',
//                        'created_at' => now(),
//                        'updated_at' => now()],
//                        ['user_id' => 3,
//                            'activity_id' => 10,
//                            'is_announced_absent' => 1,
//                            'reason' => 'Familiefeest',
//                            'created_at' => now(),
//                            'updated_at' => now()]
//                    ]);



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
