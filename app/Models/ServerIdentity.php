<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * サーバ接続情報
 */
class ServerIdentity extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'je_port',
        'be_port',
        'is_verify',
    ];

    protected $casts = [
        'is_verify' => 'boolean',
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
