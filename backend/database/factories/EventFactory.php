<?php

namespace Database\Factories;

use App\Models\Calendar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'all_day' => fake()->boolean(),
            'start' => fake()->dateTime(),
            'end' => fake()->dateTime(),
            'calendar_id' => Calendar::query()->first()->id,
        ];
    }
}
