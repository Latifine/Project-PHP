<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public function trainingMatch()
    {
        return $this->hasMany(TrainingMatch::class); //R14
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class); //R8
    }

    public function personPerTask()
    {
        return $this->hasMany(PersonPerTask::class); //R7
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class)->withDefault(); //R1
    }

    public function registration()
    {
        return $this->hasMany(Registration::class); //R6
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withDefault(); //R3
    }

    public function clothingPerPlayer()
    {
        return $this->hasMany(ClothingPerPlayer::class); //R2
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parents_per_child', 'user_child_id', 'user_parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'parents_per_child', 'user_parent_id', 'user_child_id');
    }

    public function carpoolPerson()
    {
        return $this->hasMany(CarpoolPerson::class); //R15
    }

    public function carpool()
    {
        return $this->hasMany(Carpool::class); //R10
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
