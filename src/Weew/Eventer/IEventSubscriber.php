<?php

namespace Weew\Eventer;

interface IEventSubscriber {
    /**
     * @param IEvent $event
     */
    function handle(IEvent $event);
}
