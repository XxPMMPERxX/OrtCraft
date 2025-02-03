<?php

namespace App\Models;

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
}
