<?php

namespace Weew\Events;

interface IEventSubscriptionInvoker {
    /**
     * @param IEventSubscription $subscription
     * @param IEvent $event
     *
     * @return bool
     */
    function invoke(IEventSubscription $subscription, IEvent $event);
}
