<?php

namespace App;

enum ServerType: int
{
    case JAVA = 10;
    case BE = 11;

    public function label()
    {
        return match ($this) {
            static::JAVA => 'Java版',
            static::BE => '統合版',
        };
    }
}
