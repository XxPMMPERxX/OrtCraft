<?php

namespace App\Models;

use App\Casts\Split;
use App\Enums\ServerMemberRole;
use App\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'address',
        'port',
        'description',
        'platform',
        'tags',
    ];

    protected $casts = [
        'tags' => Split::class,
    ];

    protected static function booted()
    {
        static::creating(function (Server $server) {
            empty($server->id) && $server->id = Str::uuid();
        });
    }

    /**
     * @return BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'server_members')
            ->withTimestamps()
            ->withPivot([
                'user_role'
            ]);
    }

    /**
     * @return static
     */
    public static function register(array $attributes = [])
    {
        /**
         * サーバを作成
         * @var static
         */
        $server = self::create($attributes);

        // 作成時のユーザーをオーナにセットする
        $server->members()->attach(Auth::user(), ['user_role' => ServerMemberRole::Owner]);

        return $server;
    }
}
