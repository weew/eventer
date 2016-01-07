<?php

namespace Weew\Eventer\Invokers;

use Weew\Eventer\IEvent;
use Weew\Eventer\IEventSubscriptionInvoker;
use Weew\Eventer\IEventSubscription;

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
            $this->invokeSubscriber($subscriber, $event);

            return true;
        }

        return false;
    }

    /**
     * @param callable $subscriber
     * @param IEvent $event
     */
    protected function invokeSubscriber(callable $subscriber, IEvent $event) {
        $subscriber($event);
    }
}
