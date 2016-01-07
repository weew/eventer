<?php

namespace Tests\Weew\Eventer\Stubs;

use Weew\Eventer\IEvent;
use Weew\Eventer\IEventSubscriber;

class CustomSubscriber implements IEventSubscriber {
    public function handle(IEvent $event) {
        /** @var CustomEvent $event */
        $shared = &$event->getShared();
        $shared[] = 2;
    }
}
