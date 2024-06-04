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
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template');
            $table->timestamps();
        });
        DB::table('mail_templates')->insert([
            ['template' => 'Welcome Email', 'created_at' => now(), 'updated_at' => now()],
            ['template' => 'Notification Email', 'created_at' => now(), 'updated_at' => now()],
            // Add more mail templates as needed
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_templates');
    }
};
