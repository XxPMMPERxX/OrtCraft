<?php

namespace App\Enums;

enum ServerMemberRole: int
{
    use ConvertFrontTrait;

    /** サーバーオーナー */
    case OWNER = 10;

    /** 権限者 */
    case ADMIN = 11;

    /** メンバー */
    // case Member = 12;

    public function label(): string
    {
        return match ($this) {
            static::OWNER => 'オーナー',
            static::ADMIN => '権限者',
        };
    }
}
