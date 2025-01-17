<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Facades\Auth;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Kreait\Firebase\Auth\UserRecord;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string  $id
 * @property string  $name
 * @property ?string $icon_path
 * @property ?string $comment
 * @property ?string $description
 * @property string  $firebase_id
 * @property ?string $minecraft_be_uid
 * @property ?string $minecraft_be_gamertag
 * @property ?string $minecraft_java_uid
 * @property ?string $minecraft_java_gamertag
 *
 * @property Collection<User> $friends
 * @property ?UserRecord $firebaseUser
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
        'comment',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pivot',
        'firebase_id',
        'minecraft_be_uid',
        'minecraft_java_uid',
    ];

    protected $appends = [
        'is_friend',
    ];

    /**
     * firebaseのトークンから得たユーザの情報
     */
    public $firebase_claims = null;

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


    public function friends()
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'user_id_1',
            'user_id_2',
        );
    }


    public function isFriend(): Attribute
    {
        return Attribute::get(
            function () {
                $user = Auth::user();

                if ($user === null) {
                    return false;
                }

                return !!$user->friends
                    ->find('9dee8ef8-5196-4673-a36b-ee20512ca5c5');
            },
        );
    }


    public function firebaseUser(): Attribute
    {
        return Attribute::get(
            function () {
                /** @var \Kreait\Firebase\Auth */
                $auth = app('firebase.auth');
                $firebaseUser = null;

                try {
                    $firebaseUser = $auth->getUser($this->firebase_id);
                } catch (Exception $e) {
                    //
                }

                return $firebaseUser;
            },
        );
    }


    public static function search(array $params)
    {
        $query = self::query();

        if (array_key_exists('name', $params)) {
            $name = $params['name'];
            if (is_null($name)) {
                $query->where('name', $name);
            } else {
                $query->where('name', 'like', '%' . addcslashes($name, '%_\\') . '%');
            }
        }

        if (!empty($params['ignore_self'])) {
            $query->where('id', '!=', Auth::id());
        }

        return $query;
    }
}
