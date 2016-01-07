<?php

namespace Weew\Eventer;

class EventSubscription implements IEventSubscription {
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    protected $eventName;

    /**
     * @var mixed
     */
    protected $subscriber;

    /**
     * @param $id
     * @param $eventName
     * @param $subscriber
     */
    public function __construct($id, $eventName, $subscriber) {
        $this->id = $id;
        $this->setEventName($eventName);
        $this->setSubscriber($subscriber);
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEventName() {
        return $this->eventName;
    }

    /**
     * @param $eventName
     */
    public function setEventName($eventName) {
        $this->eventName = $eventName;
    }

    /**
     * @return mixed
     */
    public function getSubscriber() {
        return $this->subscriber;
    }

    /**
     * @param $subscriber
     */
    public function setSubscriber($subscriber) {
        $this->subscriber = $subscriber;
    }
}
