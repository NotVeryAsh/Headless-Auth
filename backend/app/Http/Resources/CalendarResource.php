<?php

namespace App\Http\Resources;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var $this Calendar
         */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
