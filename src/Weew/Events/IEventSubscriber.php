<?php

namespace Weew\Events;

interface IEventSubscriber {
    /**
     * @param IEvent $event
     */
    function handle(IEvent $event);
}
