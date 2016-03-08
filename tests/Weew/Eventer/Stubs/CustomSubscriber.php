<?php

namespace Tests\Weew\Eventer\Stubs;

use Weew\Eventer\IEvent;

class CustomSubscriber {
    public function handle(IEvent $event) {
        /** @var CustomEvent $event */
        $shared = &$event->getShared();
        $shared[] = 2;
    }
}
