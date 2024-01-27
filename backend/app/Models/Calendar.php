<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Calendar
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CalendarEvent> $calendarEvents
 * @property-read int|null $calendar_events_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\CalendarFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar query()
 * @mixin \Eloquent
 */
class Calendar extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'title',
        'user_id'
    ];

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
