<?php

namespace Weew\Events;

class Subscription implements ISubscription {
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var mixed
     */
    protected $subscriber;

    /**
     * @param $id
     * @param $topic
     * @param $subscriber
     */
    public function __construct($id, $topic, $subscriber) {
        $this->id = $id;
        $this->setTopic($topic);
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
    public function getTopic() {
        return $this->topic;
    }

    /**
     * @param $topic
     */
    public function setTopic($topic) {
        $this->topic = $topic;
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
