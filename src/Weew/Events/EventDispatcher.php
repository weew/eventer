<?php

namespace Weew\Events;

use Exception;
use Weew\Events\Invokers\CallableInvoker;
use Weew\Events\Invokers\SubscriberInvoker;

class EventDispatcher implements IEventDispatcher {
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
     * @throws Exception
     */
    public function dispatch($event) {
        if ( ! $event instanceof IEvent) {
            $event = $this->createEvent($event);
        }

        foreach ($this->subscriptions as $subscription) {
            if ( ! $event->isActive()) {
                return;
            }

            if ($subscription === null ||
                $subscription->getTopic() !== $event->getTopic()
            ) {
                continue;
            }

            $this->invokeSubscription($subscription, $event);
        }
    }

    /**
     * @param string $topic
     * @param $abstract
     *
     * @return EventSubscription
     */
    public function subscribe($topic, $abstract) {
        $subscription = $this->createSubscription(
            $this->generateSubscriptionId(), $topic, $abstract
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

        $description = $subscription->getSubscriber();

        if ( ! is_scalar($description)) {
            $description = 'callable';
        }

        throw new Exception(
            s('Could not invoke subscriber %s.', $description)
        );
    }

    /**
     * @param $id
     * @param $topic
     * @param $subscriber
     *
     * @return EventSubscription
     */
    protected function createSubscription($id, $topic, $subscriber) {
        return new EventSubscription(
            $id, $topic, $subscriber
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
     * @param $topic
     * @param array $data
     *
     * @return GenericEvent
     */
    protected function createEvent($topic, array $data = []) {
        return new GenericEvent($topic, $data);
    }
}
