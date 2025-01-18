<?php

namespace App\Http\Resources;

use App\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User */
        $user = $this->resource;

        /** フレンド関連  */
        if ($request->for_friend) {
            $user->append([
                'is_friend',
                'already_sent_friend_request',
            ]);
        }

        if ($request->for_member) {
            //
        }

        return $user->toArray();
    }
}
