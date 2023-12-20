<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "phone_number",
        "avatar",
        "last_login_date",
        "role",
        "address",
        "email_verified_at"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function providers()
    {
        return $this->hasMany(Providers::class, 'user_id', 'id');
    }

    public function sendPasswordResetNotification($token): void
    {
        $appUrl = env('APP_URL');

        $url = $appUrl . 'api/reset_password?token=' . $token . "&email=" . $this->email;


        $this->notify(new ResetPasswordNotification($url));
    }
}
