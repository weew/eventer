<?php

namespace Weew\Events;

class Event implements IEvent {
    /**
     * @var string
     */
    protected $topic;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $status = EventStatus::ACTIVE;

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null) {
        return array_get($this->data, $key, $default);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        array_set($this->data, $key, $value);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key) {
        return array_has($this->data, $key);
    }

    /**
     * @param $key
     */
    public function remove($key) {
        array_remove($this->data, $key);
    }

    /**
     * @return string
     */
    public function getTopic() {
        if ($this->topic === null) {
            return get_class($this);
        }

        return $this->topic;
    }

    /**
     * @param $topic
     */
    public function setTopic($topic) {
        $this->topic = $topic;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data) {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->status == EventStatus::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isHandled() {
        return $this->status == EventStatus::HANDLED;
    }

    /**
     * Tag event as handled.
     */
    public function handle() {
        $this->status = EventStatus::HANDLED;
    }
}
