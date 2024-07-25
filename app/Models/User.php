<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string  $id
 * @property string  $name
 * @property string  $firebase_id
 * @property ?string $minecraft_uid
 * @property ?string $minecraft_gamertag
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

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

    public function servers()
    {
        return $this->belongsToMany(Server::class, 'server_members')
            ->withTimestamps()
            ->withPivot([
                'user_role'
            ]);
    }

    /**
     * マイクラが認証済みかどうか
     */
    public function getIsVerifiedMinecraftAttribute()
    {
        return !is_null($this->minecraft_uid) && !is_null($this->minecraft_gamertag);
    }
}
