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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name');
            $table->date('date_of_birth');
            $table->string('street_number');
            $table->string('postal_code');
            $table->string('municipality');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->boolean('permission_photos')->default(false);
            $table->foreignId('gender_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK1
            $table->foreignId('role_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); //FK3
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_registered')->default(false);
            $table->boolean('is_secondParent')->default(false);
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
        DB::table('users')->insert([
            [
                'name' => 'Doe',
                'first_name' => 'John',
                'date_of_birth' => '1990-01-01',
                'street_number' => '123 Main St',
                'postal_code' => '12345',
                'municipality' => 'Cityville',
                'phone_number' => '555-1234',
                'email' => 'john.doe@example.com',
                'permission_photos' => true,
                'gender_id' => 1, // Replace with the appropriate gender_id
                'role_id' => 1, // Replace with the appropriate role_id
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_registered' => true,
                'is_secondParent' => false,
                'is_active' => true,
                'profile_photo_path' => 'path/to/profile-photo.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Doe',
                'first_name' => 'Jane',
                'date_of_birth' => '1992-02-02',
                'street_number' => '456 Oak St',
                'postal_code' => '56789',
                'municipality' => 'Townsville',
                'phone_number' => '555-5678',
                'email' => 'jane.doe@example.com',
                'permission_photos' => false,
                'gender_id' => 2, // Replace with the appropriate gender_id
                'role_id' => 2, // Replace with the appropriate role_id
                'email_verified_at' => now(),
                'password' => Hash::make('password456'),
                'is_registered' => true,
                'is_secondParent' => true,
                'is_active' => true,
                'profile_photo_path' => 'path/to/profile-photo.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users as needed
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
