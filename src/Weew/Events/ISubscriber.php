<?php

namespace Weew\Events;

interface ISubscriber {
    /**
     * @param IEvent $event
     */
    function handle(IEvent $event);
}
