<?php

namespace Weew\Events\Invokers;

use Weew\Events\IEvent;
use Weew\Events\ISubscriptionInvoker;
use Weew\Events\ISubscriber;
use Weew\Events\ISubscription;

class SubscriberInvoker implements ISubscriptionInvoker {
    /**
     * @param ISubscription $subscription
     * @param IEvent $event
     *
     * @return bool
     */
    public function invoke(ISubscription $subscription, IEvent $event) {
        $subscriber = $subscription->getSubscriber();

        if ($this->invokeSubscriberByInstance($subscriber, $event) ||
            $this->invokeSubscriberByClassName($subscriber, $event)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param $subscriber
     * @param IEvent $event
     *
     * @return bool
     */
    protected function invokeSubscriberByInstance($subscriber, IEvent $event) {
        if ($subscriber instanceof ISubscriber) {
            $subscriber->handle($event);

            return true;
        }

        return false;
    }

    /**
     * @param $subscriber
     * @param IEvent $event
     *
     * @return bool
     */
    protected function invokeSubscriberByClassName($subscriber, IEvent $event) {
        if (is_string($subscriber) &&
            class_exists($subscriber) &&
            in_array(ISubscriber::class, class_implements($subscriber))
        ) {
            /** @var ISubscriber $subscriber */
            $subscriber = new $subscriber();
            $subscriber->handle($event);

            return true;
        }

        return false;
    }
}
