<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var $this Event
         */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'all_day' => $this->all_day,
            'start' => $this->start,
            'end' => $this->end,
            'calendar_id' => $this->calendar_id,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
        ];
    }
}
