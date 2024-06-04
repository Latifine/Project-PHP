<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parents_per_child', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_child_id')->constrained('users')->onDelete('restrict')->onUpdate('restrict'); //FK4
            $table->foreignId('user_parent_id')->constrained('users')->onDelete('restrict')->onUpdate('restrict'); //FK5
            $table->timestamps();
        });
        // Dummy data for Parent Per Children
        $childIds = DB::table('users')->pluck('id')->toArray();


//            DB::table('parents_per_child')->insert([
//                ['user_child_id' => 3,
//                'user_parent_id' => 1,
//                'created_at' => now(),
//                'updated_at' => now()],
//                ['user_child_id' => 3,
//                    'user_parent_id' => 2,
//                    'created_at' => now(),
//                    'updated_at' => now()],
//            ]);

    }





    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents_per_child');
    }
};
