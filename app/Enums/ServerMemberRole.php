<?php

namespace App\Enums;

enum ServerMemberRole: int
{
    /** サーバーオーナー */
    case Owner = 10;

    /** 権限者 */
    case Admin = 11;

    /** メンバー */
    // case Member = 12;

    public function label()
    {
        return match ($this) {
            static::Owner => 'オーナー',
            static::Admin => '権限者',
        };
    }
}
