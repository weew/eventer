<?php

namespace Tests\Weew\Events\Stubs;

use Weew\Events\IEvent;
use Weew\Events\ISubscriber;

class CustomSubscriber implements ISubscriber {
    public function handle(IEvent $event) {
        $shared = &$event->getShared();
        $shared[] = 2;
    }
}
