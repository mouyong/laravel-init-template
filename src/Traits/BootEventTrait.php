<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

use Illuminate\Support\Facades\Event;
use Stancl\JobPipeline\JobPipeline;

trait BootEventTrait
{
    abstract public function events(): array ;

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    }

    protected function makeJobPipeline(array $jobs, callable $callable)
    {
        return JobPipeline::make($jobs)
            ->send($callable)
            ->shouldBeQueued(false); // `false` by default, but you probably want to make this `true` for production.
    }
}