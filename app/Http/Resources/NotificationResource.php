<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $notification = $this->resource;

        return array_merge(
            $notification->toArray(),
            [
                'created_at' => $notification->created_at->format('Y/m/d H:i'),
            ],
        );
    }
}
