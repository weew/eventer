<?php

namespace Weew\Events;

use Exception;
use Weew\Events\Invokers\CallableInvoker;
use Weew\Events\Invokers\SubscriberInvoker;

class EventDispatcher implements IEventDispatcher {
    /**
     * @var ISubscription[]
     */
    protected $subscriptions = [];

    /**
     * @var ISubscriptionInvoker[]
     */
    protected $invokers = [];

    /**
     * @param ISubscriptionInvoker[] $invokers
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
     * @return Subscription
     */
    public function subscribe($topic, $abstract) {
        $subscription = $this->createSubscription(
            $this->generateSubscriptionId(), $topic, $abstract
        );
        $this->subscriptions[$subscription->getId()] = $subscription;

        return $subscription;
    }

    /**
     * @param ISubscription $subscription
     */
    public function unsubscribe(ISubscription $subscription) {
        if (array_has($this->subscriptions, $subscription->getId())) {
            array_set($this->subscriptions, $subscription->getId(), null);
        }
    }

    /**
     * @return ISubscription[]
     */
    public function getSubscriptions() {
        return $this->subscriptions;
    }

    /**
     * @param ISubscription[] $subscriptions
     */
    public function setSubscriptions(array $subscriptions) {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @return ISubscriptionInvoker[]
     */
    public function getSubscriptionInvokers() {
        return $this->invokers;
    }

    /**
     * @param ISubscriptionInvoker[] $invokers
     */
    public function setSubscriptionInvokers(array $invokers) {
        $this->invokers = $invokers;
    }

    /**
     * @param ISubscriptionInvoker $invoker
     */
    public function addSubscriptionInvoker(ISubscriptionInvoker $invoker) {
        $this->invokers[] = $invoker;
    }

    /**
     * @return int
     */
    protected function generateSubscriptionId() {
        return count($this->subscriptions);
    }

    /**
     * @param ISubscription $subscription
     * @param IEvent $event
     *
     * @throws Exception
     */
    protected function invokeSubscription(ISubscription $subscription, IEvent $event) {
        foreach ($this->invokers as $invoker) {
            if ($invoker->invoke($subscription, $event)) {
                return;
            }
        }

        $description = $subscription->getSubscriber();

        if ( ! is_scalar($description)) {
            $description = 'Callable';
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
     * @return Subscription
     */
    protected function createSubscription($id, $topic, $subscriber) {
        return new Subscription(
            $id, $topic, $subscriber
        );
    }

    /**
     * @return ISubscriptionInvoker[]
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
