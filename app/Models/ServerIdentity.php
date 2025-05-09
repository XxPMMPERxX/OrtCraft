<?php

namespace App\Models;

use App\Libs\Common;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * サーバ接続情報
 *
 * @property int $id
 * @property int $server_id
 * @property string $address
 * @property ?int $je_port
 * @property ?int $be_port
 * @property ?string $auth_code
 * @property ?string $activated_at
 * @property boolean $is_verify
 */
class ServerIdentity extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'address',
        'je_port',
        'be_port',
    ];

    protected $casts = [
        'is_verify' => 'boolean',
    ];

    protected $hidden = [
        'auth_code',
    ];


    public function server()
    {
        return $this->belongsTo(Server::class);
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


    public function activate()
    {
        $this->activated_at = now();
        $this->save();
    }


    /**
     * 接続情報を認可する
     *
     * @param bool $force trueの場合認証フローを経ずに認可する
     */
    public function verify(bool $force = false)
    {
        if (!$force) {
            $this->is_verify = true;
            return $this->save();
        }

        $ports = $this->only([
            'je_port',
            'be_port',
        ]);
        $ports = array_filter($ports);

        foreach ($ports as $type => $port) {
            try {
                $data = (
                    $type === 'je_port'
                        ? Common::pingJE($this->address, $port)
                        : Common::pingBE($this->address, $port)
                );

                if ($data['motd'] !== $this->auth_code) {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }

        $this->is_verify = true;
        return $this->save();
    }
}
