<?php

namespace App\Http\Resources;

use App\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Server;

class ServerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Server */
        $server = $this->resource;

        $user = Auth::user();

        if ($user->servers()->where('servers.id', $server->id)->exists()) {
            $server->load(['identity', 'identities']);
            $server->identity?->makeVisible(['auth_code']);
        }

        return $server->toArray();
    }
}
