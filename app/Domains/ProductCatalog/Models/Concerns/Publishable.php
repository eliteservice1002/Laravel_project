<?php

namespace App\Domains\ProductCatalog\Models\Concerns;

use App\Domains\ProductCatalog\Models\Scopes\PublishedScope;

/**
 * @property bool $published
 */
trait Publishable
{
    /**
     * Boot the publishable trait for a model.
     */
    public static function bootPublishable(): void
    {
        static::addGlobalScope(new PublishedScope());
    }

    public static function publishing($callback): void
    {
        static::registerModelEvent('publishing', $callback);
    }

    public static function unpublishing($callback): void
    {
        static::registerModelEvent('unpublishing', $callback);
    }

    public static function published($callback): void
    {
        static::registerModelEvent('published', $callback);
    }

    public static function unpublished($callback): void
    {
        static::registerModelEvent('unpublished', $callback);
    }

    public function initializePublishable(): void
    {
        if ( ! isset($this->casts['published'])) {
            $this->casts['published'] = 'boolean';
        }
    }

    public function publish(): bool
    {
        if ($this->fireModelEvent('publishing') === false) {
            return false;
        }

        $this->published = true;

        $result = $this->save();

        $this->fireModelEvent('published', false);

        return $result;
    }

    public function unpublish(): bool
    {
        if ($this->fireModelEvent('unpublishing') === false) {
            return false;
        }

        $this->published = false;

        $result = $this->save();

        $this->fireModelEvent('unpublished', false);

        return $result;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function getQualifiedPublishedColumn(): string
    {
        return $this->qualifyColumn('published');
    }

    private function setPublishedAttribute($value): void
    {
        $this->attributes['published'] = $value;
    }
}
