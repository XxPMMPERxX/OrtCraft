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

        $user->setVisible([
            'id',
            'minecraft_be_gamertag',
            'minecraft_be_uid',
            'minecraft_java_gamertag',
            'minecraft_java_uid',
            'name',
        ]);

        return parent::toArray($request);
    }
}
