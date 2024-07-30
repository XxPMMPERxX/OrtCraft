<?php

namespace App\Enums;

use BackedEnum;
use Illuminate\Support\Str;

trait ConvertFrontTrait
{
    abstract public function label(): string;

    public static function toArray()
    {
        $array = array_map(function (BackedEnum|self $case) {
            return [
                Str::upper($case->name) => [
                    'name' => $case->name,
                    'value' => $case->value,
                    'label' => $case->label(),
                ],
            ];
        }, self::cases());

        return array_merge(...$array);
    }
}
