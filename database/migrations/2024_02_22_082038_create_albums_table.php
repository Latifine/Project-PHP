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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
        });

//        DB::table('albums')->insert([
//            [
//                'name' => 'Album 1',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name' => 'Album 2',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            // Add more photos as needed
//        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
