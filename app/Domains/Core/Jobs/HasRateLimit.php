<?php

namespace App\Domains\Core\Jobs;

use Illuminate\Support\Facades\Redis;

/**
 * @method void release(int $delay)
 */
trait HasRateLimit
{
    /**
     * Set the amount of time to block until a lock is available.
     */
    protected int $concurrency = 1;

    /**
     * Set the amount of time to block until a lock is available.
     */
    protected int $lockLifetime = 5;

    /**
     * The amount of time to block until a lock is available in seconds.
     */
    protected int $blockTimeout = 3;

    /**
     * Execute the job.
     *
     * @throws \Illuminate\Contracts\Redis\LimiterTimeoutException
     */
    public function handle(): void
    {
        Redis::throttle('key')
            ->block($this->blockTimeout)
            ->allow($this->concurrency)
            ->every($this->lockLifetime)
            ->then([$this, 'handleJob'], [$this, 'handleFailure']);
    }

    abstract protected function handleJob(): void;

    protected function handleFailure(): void
    {
        $this->release(5);
    }
}
