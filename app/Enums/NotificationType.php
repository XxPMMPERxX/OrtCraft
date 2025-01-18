<?php

namespace App\Enums;

use App\Notifications\FriendRequest;
use App\Notifications\MemberInvitation;

enum NotificationType: string
{
    use ConvertFrontTrait;

    case FriendRequest = FriendRequest::class;
    case MemberInvitation = MemberInvitation::class;

    public function label(): string
    {
        return match ($this) {
            self::FriendRequest => '友達リクエスト',
            self::MemberInvitation => 'サーバメンバー招待',
        };
    }
}
