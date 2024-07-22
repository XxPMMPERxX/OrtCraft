<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * @property string  $id
 * @property string  $name
 * @property string  $firebase_id
 * @property ?string $minecraft_uid
 * @property ?string $minecraft_gamertag
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'firebase_id',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        ];
    }

    //ここから追記
    protected static function booted()
    {
        static::creating(function (User $user) {
            empty($user->id) && $user->id = Str::uuid();
        });
    }

    public function servers()
    {
        return $this->hasMany(Server::class);
    }

    /**
     * マイクラが認証済みかどうか
     */
    public function getIsVerifiedMinecraftAttribute()
    {
        return !is_null($this->minecraft_uid) && !is_null($this->minecraft_gamertag);
    }
}
