<?php

namespace Weew\Eventer;

use Exception;
use Weew\Eventer\Invokers\CallableInvoker;
use Weew\Eventer\Invokers\SubscriberInvoker;

class Eventer implements IEventer {
    /**
     * @var IEventSubscription[]
     */
    protected $subscriptions = [];

    /**
     * @var IEventSubscriptionInvoker[]
     */
    protected $invokers = [];

    /**
     * @param IEventSubscriptionInvoker[] $invokers
     */
    public function __construct(array $invokers = null) {
        if ( ! is_array($invokers)) {
            $invokers = $this->createDefaultInvokers();
        }

        foreach ($invokers as $invoker) {
            $this->addSubscriptionInvoker($invoker);
        }
    }

    /**
     * @param $event
     *
     * @return IEvent
     * @throws Exception
     */
    public function dispatch($event) {
        if ( ! $event instanceof IEvent) {
            $event = $this->createEvent($event);
        }

        foreach ($this->subscriptions as $subscription) {
            if ( ! $event->isActive()) {
                return $event;
            }

            if ($subscription === null ||
                $subscription->getEventName() !== $event->getName()
            ) {
                continue;
            }

            $this->invokeSubscription($subscription, $event);
        }

        return $event;
    }

    /**
     * @param string $eventName
     * @param $abstract
     *
     * @return EventSubscription
     */
    public function subscribe($eventName, $abstract) {
        $subscription = $this->createSubscription(
            $this->generateSubscriptionId(), $eventName, $abstract
        );
        $this->subscriptions[$subscription->getId()] = $subscription;

        return $subscription;
    }

    /**
     * @param IEventSubscription $subscription
     */
    public function unsubscribe(IEventSubscription $subscription) {
        if (array_has($this->subscriptions, $subscription->getId())) {
            array_set($this->subscriptions, $subscription->getId(), null);
        }
    }

    /**
     * @return IEventSubscription[]
     */
    public function getSubscriptions() {
        return $this->subscriptions;
    }

    /**
     * @param IEventSubscription[] $subscriptions
     */
    public function setSubscriptions(array $subscriptions) {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @return IEventSubscriptionInvoker[]
     */
    public function getSubscriptionInvokers() {
        return $this->invokers;
    }

    /**
     * @param IEventSubscriptionInvoker[] $invokers
     */
    public function setSubscriptionInvokers(array $invokers) {
        $this->invokers = $invokers;
    }

    /**
     * @param IEventSubscriptionInvoker $invoker
     */
    public function addSubscriptionInvoker(IEventSubscriptionInvoker $invoker) {
        $this->invokers[] = $invoker;
    }

    /**
     * @return int
     */
    protected function generateSubscriptionId() {
        return count($this->subscriptions);
    }

    /**
     * @param IEventSubscription $subscription
     * @param IEvent $event
     *
     * @throws Exception
     */
    protected function invokeSubscription(IEventSubscription $subscription, IEvent $event) {
        foreach ($this->invokers as $invoker) {
            if ($invoker->invoke($subscription, $event)) {
                return;
            }
        }

        $subscriber = $subscription->getSubscriber();

        throw new Exception(
            s('Could not invoke subscriber "%s".',
                is_scalar($subscriber) ? $subscriber : get_type($subscriber))
        );
    }

    /**
     * @param $id
     * @param $eventName
     * @param $subscriber
     *
     * @return EventSubscription
     */
    protected function createSubscription($id, $eventName, $subscriber) {
        return new EventSubscription(
            $id, $eventName, $subscriber
        );
    }

    /**
     * @return IEventSubscriptionInvoker[]
     */
    protected function createDefaultInvokers() {
        return [
            new CallableInvoker(),
            new SubscriberInvoker(),
        ];
    }

    /**
     * @param $eventName
     * @param array $data
     *
     * @return IEvent
     */
    protected function createEvent($eventName, array $data = []) {
        return new GenericEvent($eventName, $data);
    }
}
