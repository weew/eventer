<?php

namespace Weew\Events\Invokers;

use Weew\Events\IEvent;
use Weew\Events\IEventSubscriptionInvoker;
use Weew\Events\IEventSubscription;

class CallableInvoker implements IEventSubscriptionInvoker {
    /**
     * @param IEventSubscription $subscription
     * @param IEvent $event
     *
     * @return bool
     */
    public function invoke(IEventSubscription $subscription, IEvent $event) {
        $subscriber = $subscription->getSubscriber();

        if (is_callable($subscriber)) {
            $subscriber($event);

            return true;
        }

        return false;
    }
}
