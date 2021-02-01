<?php

namespace App\Domains\Core\Jobs;

use function Amp\call;
use Exception;

/**
 * @property int $timeout
 * @property int $tries
 */
class DomainActionJob extends Job
{
    protected string $actionClass;

    protected array $parameters;

    /** @var callable */
    protected $onFailCallback;

    public function __construct($action, array $parameters = [])
    {
        $this->actionClass = is_string($action) ? $action : get_class($action);
        $this->parameters = $parameters;

        if (is_object($action) && method_exists($action, 'failed')) {
            $this->onFailCallback = [$action, 'failed'];
        }

        $this->resolveQueueableProperties($action);
    }

    public function displayName(): string
    {
        return $this->actionClass;
    }

    public function failed(Exception $exception): void
    {
        if (isset($this->onFailCallback)) {
            call($this->onFailCallback, [$exception]);
        }
    }

    public function handle(): void
    {
        $action = app($this->actionClass);
        $action->execute(...$this->parameters);
    }

    protected function resolveQueueableProperties($action): void
    {
        $queueableProperties = [
            'connection',
            'queue',
            'chainConnection',
            'chainQueue',
            'delay',
            'chained',
            'tries',
            'timeout',
        ];

        foreach ($queueableProperties as $queueableProperty) {
            if (property_exists($action, $queueableProperty)) {
                $this->{$queueableProperty} = $action->{$queueableProperty};
            }
        }
    }
}
