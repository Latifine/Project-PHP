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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task');
            $table->timestamps();
        });
//        DB::table('tasks')->insert([
//            ['task' => 'Ballen meenemen', 'created_at' => now(), 'updated_at' => now()],
//            ['task' => 'Water halen', 'created_at' => now(), 'updated_at' => now()],
//            ['task' => 'Inkom', 'created_at' => now(), 'updated_at' => now()],
//            // Add more tasks as needed
//        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
