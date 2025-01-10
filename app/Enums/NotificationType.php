<?php

namespace App\Enums;

use App\Notifications\FriendRequest;

enum NotificationType: string
{
    use ConvertFrontTrait;

    case FriendRequest = FriendRequest::class;

    public function label(): string
    {
        return match ($this) {
            self::FriendRequest => '友達リクエスト',
        };
    }
}
