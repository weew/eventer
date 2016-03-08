<?php

namespace Weew\Eventer\Invokers;

use Weew\Eventer\IEvent;
use Weew\Eventer\IEventSubscriptionInvoker;
use Weew\Eventer\IEventSubscription;

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
        if (is_object($subscriber) && method_exists($subscriber, 'handle')) {
            $this->invokeSubscriber($subscriber, $event);

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
            method_exists($subscriber, 'handle')
        ) {
            $subscriber = $this->createSubscriber($subscriber);
            $this->invokeSubscriber($subscriber, $event);

            return true;
        }

        return false;
    }

    /**
     * @param $class
     *
     * @return object
     */
    protected function createSubscriber($class) {
        return new $class();
    }

    /**
     * @param object $subscriber
     * @param IEvent $event
     */
    protected function invokeSubscriber($subscriber, IEvent $event) {
        $subscriber->handle($event);
    }
}
