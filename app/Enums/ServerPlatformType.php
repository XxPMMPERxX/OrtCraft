<?php

namespace App\Enums;

enum ServerPlatformType: int
{
    use ConvertFrontTrait;

    case JAVA = 10;
    case BE = 11;
    case JAVA_BE = 12;

    public function label(): string
    {
        return match ($this) {
            static::JAVA => 'Java版',
            static::BE => '統合版',
            static::JAVA_BE => 'Java版/統合版',
        };
    }
}
