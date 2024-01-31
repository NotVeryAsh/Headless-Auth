<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CalendarEvent
 *
 * @property int $id
 * @property string $title
 * @property bool $all_day
 * @property string $start
 * @property string $end
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\CalendarEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereAllDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereUserId($value)
 *
 * @property-read \App\Models\Calendar|null $calendar
 *
 * @method static Builder|CalendarEvent whereDuring($start, $end)
 *
 * @mixin \Eloquent
 */
class CalendarEvent extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'title',
        'all_day',
        'start',
        'end',
        'calendar_id',
    ];

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class);
    }

    public function scopeWhereDuring(Builder $query, $start, $end): void
    {
        $query->whereBetween('start', [$start, $end])
            ->orWhereBetween('end', [$start, $end]);
    }
}
