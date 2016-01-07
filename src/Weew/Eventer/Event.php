<?php

namespace Weew\Eventer;

abstract class Event implements IEvent {
    /**
     * @var string
     */
    protected $name;

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
    public function getName() {
        if ($this->name === null) {
            return get_class($this);
        }

        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name) {
        $this->name = $name;
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
