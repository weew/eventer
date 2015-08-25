<?php

namespace Weew\Events\Invokers;

use Weew\Events\IEvent;
use Weew\Events\IEventSubscriptionInvoker;
use Weew\Events\IEventSubscriber;
use Weew\Events\IEventSubscription;

class SubscriberInvoker implements IEventSubscriptionInvoker {
    /**
     * @param IEventSubscription $subscription
     * @param IEvent $event
     *
     * @return bool
     */
    public function invoke(IEventSubscription $subscription, IEvent $event) {
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
        if ($subscriber instanceof IEventSubscriber) {
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
            in_array(IEventSubscriber::class, class_implements($subscriber))
        ) {
            /** @var IEventSubscriber $subscriber */
            $subscriber = new $subscriber();
            $subscriber->handle($event);

            return true;
        }

        return false;
    }
}
