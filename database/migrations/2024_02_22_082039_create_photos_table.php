<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new  class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId("album_id")->constrained()->onDelete('cascade')->onUpdate('cascade'); //FK17
            $table->timestamps();
        });

        /*$albumIds = DB::table('albums')->pluck('id')->toArray();

        foreach ($albumIds as $albumId) {
            if ($albumId === 1) {
                DB::table('photos')->insert([
                    [
                        'album_id' => $albumId,
                        'name' => '3df3b60f-d6e1-3af9-913f-0014e73650ee',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'album_id' => $albumId,
                        'name' => '8f156938-6462-3b3e-84ba-bfc7dd232c34',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
            if ($albumId === 2) {
                DB::table('photos')->insert([
                    'album_id' => $albumId,
                    'name' => '7dc5edce-ead6-41e4-9c4b-240223c9bab0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }*/
        /*DB::table('photos')->insert([
            [
                'album_id' => '0',
                'name' => '3df3b60f-d6e1-3af9-913f-0014e73650ee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'album_id' => '1',
                'name' => '4bcaf5b9-76ba-4891-934b-1a441c62b008',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'album_id' => '1',
                'name' => '7dc5edce-ead6-41e4-9c4b-240223c9bab0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more photos as needed
        ]);*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
