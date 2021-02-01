<?php

namespace App\Domains\Core\Actions;

use App\Domains\Core\Jobs\DomainActionJob;

trait QueuedAction
{
    /**
     * @return static
     */
    public function onQueue(?string $queue = null): self
    {
        /** @var self $class */
        $class = new class($this, $queue) {
            protected object $action;

            protected ?string $queue;

            public function __construct(object $action, ?string $queue)
            {
                $this->action = $action;
                $this->onQueue($queue);
            }

            public function execute(...$parameters): void
            {
                dispatch(new DomainActionJob($this->action, $parameters))
                    ->onQueue($this->queue);
            }

            protected function onQueue(?string $queue): void
            {
                if (is_string($queue)) {
                    $this->queue = $queue;

                    return;
                }

                if (isset($this->action->queue)) {
                    $this->queue = $this->action->queue;
                }
            }
        };

        return $class;
    }
}
