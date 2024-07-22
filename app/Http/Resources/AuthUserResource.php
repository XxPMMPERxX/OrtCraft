<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class AuthUserResource extends JsonResource
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

        $user->append([
            'is_verified_minecraft',
        ]);

        $user->load('servers');

        $user->setVisible([
            'id',
            'is_verified_minecraft',
            'minecraft_gamertag',
            'minecraft_uid',
            'name',
            'servers'
        ]);

        return parent::toArray($request);
    }
}
