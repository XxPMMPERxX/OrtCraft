<?php

namespace App\Enums;

enum ServerPlatformType: int
{
    case Java = 10;
    case Be = 11;
    case JavaBe = 12;

    public function label()
    {
        return match ($this) {
            static::Java => 'Java版',
            static::Be => '統合版',
            static::JavaBe => 'Java版/統合版',
        };
    }
}
