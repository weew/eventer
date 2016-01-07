<?php

namespace Weew\Eventer;

interface IEventSubscriptionInvoker {
    /**
     * @param IEventSubscription $subscription
     * @param IEvent $event
     *
     * @return bool
     */
    function invoke(IEventSubscription $subscription, IEvent $event);
}
