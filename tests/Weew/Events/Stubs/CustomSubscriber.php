<?php

namespace Tests\Weew\Events\Stubs;

use Weew\Events\IEvent;
use Weew\Events\IEventSubscriber;

class CustomSubscriber implements IEventSubscriber {
    public function handle(IEvent $event) {
        /** @var CustomEvent $event */
        $shared = &$event->getShared();
        $shared[] = 2;
    }
}
