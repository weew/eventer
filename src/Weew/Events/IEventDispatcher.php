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
     * @return ISubscription
     */
    function subscribe($topic, $abstract);

    /**
     * @param ISubscription $subscription
     */
    function unsubscribe(ISubscription $subscription);
}
