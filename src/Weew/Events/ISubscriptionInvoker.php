<?php

namespace Weew\Events;

interface ISubscriptionInvoker {
    /**
     * @param ISubscription $subscription
     * @param IEvent $event
     *
     * @return bool
     */
    function invoke(ISubscription $subscription, IEvent $event);
}
