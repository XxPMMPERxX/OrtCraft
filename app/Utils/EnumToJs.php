<?php

namespace App\Utils;

use App\Enums\ServerMemberRole;
use App\Enums\ServerPlatformType;
use Illuminate\Support\Str;

class EnumToJs
{
    protected static $enums = [
        ServerMemberRole::class,
        ServerPlatformType::class,
    ];

    public static function toJson()
    {
        $toJson = [];
        foreach (self::$enums as $enum) {
            $enumName = Str::upper(Str::snake(class_basename($enum)));
            $toJson[$enumName] = $enum::toArray();
        }

        return json_encode($toJson);
    }
}
