<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Game $resource
 */

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $players = $this->resource->players;
        return [
            'id'        => $this->resource->id,
            'code'      => $players[0]->id,
            'invite'    => $players[1]->id,
            'success'   => true
        ];
    }
}
