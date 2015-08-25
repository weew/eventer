<?php

namespace Weew\Events;

interface IEventDispatcher {
    /**
     * @param $event
     */
    function dispatch($event);

    /**
     * @param $topic
     * @param $abstract
     *
     * @return IEventSubscription
     */
    function subscribe($topic, $abstract);

    /**
     * @param IEventSubscription $subscription
     */
    function unsubscribe(IEventSubscription $subscription);
}
