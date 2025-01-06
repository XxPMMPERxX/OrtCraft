<?php

namespace App\Models;

use App\Casts\Split;
use App\Enums\ServerMemberRole;
use App\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

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
        'tags',
    ];

    protected $casts = [
        'tags' => Split::class,
    ];

    protected $hidden = [
        'auth_code',
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
        $server->setAuthCode();

        // 作成時のユーザーをオーナにセットする
        $server->members()->attach(Auth::user(), ['user_role' => ServerMemberRole::OWNER]);

        return $server;
    }

    /**
     * 認証コードを生成して保存
     */
    public function setAuthCode()
    {
        $authCode = Str::random(10);
        $this->auth_code = $authCode;
        $this->save();
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
