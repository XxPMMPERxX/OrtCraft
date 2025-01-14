<?php

namespace App\Models;

use App\Casts\Split;
use App\Enums\ServerMemberRole;
use App\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * サーバ
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string[] $tags
 *
 * @property Collection<User> $members
 * @property Collection<ServerIdentity> $identities 作成ずみの接続情報
 * @property ?ServerIdentity $identity $identity 現在有効化中の接続情報
 */
class Server extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'description',
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


    public function identities()
    {
        return $this->hasMany(ServerIdentity::class);
    }


    public function identity()
    {
        return $this->hasOne(ServerIdentity::class)
            ->where('activated_at', '!=', null)
            ->orderBy('activated_at', 'DESC');
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

        if (!empty($params['only_own'])) {
            $query->whereHas('members', function ($query) {
                $query->where('user_id', Auth::id());
            });
        }

        $query->orderBy('id', 'DESC');

        return $query;
    }
}
