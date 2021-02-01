<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\HasUlidKey;
use App\Domains\Core\Services\ES\Event;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

/**
 * @property mixed|SchemalessAttributes|string $name
 * @property array|mixed|SchemalessAttributes $data
 * @property mixed|SchemalessAttributes $metadata
 */
class StoredEvent extends TenantModel
{
    use HasUlidKey;
    use SchemalessAttributesTrait;

    protected $schemalessAttributes = ['data', 'metadata'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (StoredEvent $storedEvent) {
            $storedEvent->metadata['occurred_at'] = $storedEvent->freshTimestamp();
        });
    }

    public static function store(Event $event): bool
    {
        $storedEvent = new StoredEvent();

        $storedEvent->name = $event->eventName();
        $storedEvent->data = [];
        $storedEvent->metadata = [
            'event_name' => $event->eventName(),
        ];

        return $storedEvent->save();
    }

    public function getDataAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'data');
    }

    public function getMetadataAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'metadata');
    }

    public function scopeStartingFrom(Builder $query, string $storedEventId): void
    {
        $query->where('id', '>=', $storedEventId);
    }
}
