<?php

namespace Weew\Events;

interface IEventDispatcher {
    /**
     * @param $event
     */
    function dispatch($event);

    /**
     * @param $eventName
     * @param $abstract
     *
     * @return IEventSubscription
     */
    function subscribe($eventName, $abstract);

    /**
     * @param IEventSubscription $subscription
     */
    function unsubscribe(IEventSubscription $subscription);
}
