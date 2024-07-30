<?php

namespace App\Models;

use App\Casts\Split;
use App\Enums\ServerMemberRole;
use App\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Server extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'address',
        'je_port',
        'be_port',
        'description',
        'platform',
        'tags',
    ];

    protected $casts = [
        'tags' => Split::class,
    ];

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
        $server->members()->attach(Auth::user(), ['user_role' => ServerMemberRole::OWNER]);

        return $server;
    }


    public static function search(array $params)
    {
        $query = self::query();
        $query->orderBy('id', 'DESC');

        return $query;
    }
}
