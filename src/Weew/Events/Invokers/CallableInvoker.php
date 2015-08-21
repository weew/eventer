<?php

namespace Weew\Events\Invokers;

use Weew\Events\IEvent;
use Weew\Events\ISubscriptionInvoker;
use Weew\Events\ISubscription;

class CallableInvoker implements ISubscriptionInvoker {
    /**
     * @param ISubscription $subscription
     * @param IEvent $event
     *
     * @return bool
     */
    public function invoke(ISubscription $subscription, IEvent $event) {
        $subscriber = $subscription->getSubscriber();

        if (is_callable($subscriber)) {
            $subscriber($event);

            return true;
        }

        return false;
    }
}
