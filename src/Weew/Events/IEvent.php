<?php

namespace Weew\Events;

interface IEvent {
    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    function get($key, $default = null);

    /**
     * @param $key
     * @param $value
     */
    function set($key, $value);

    /**
     * @param $key
     *
     * @return bool
     */
    function has($key);

    /**
     * @param $key
     */
    function remove($key);

    /**
     * @return string
     */
    function getName();

    /**
     * @param $name
     */
    function setName($name);

    /**
     * @return array
     */
    function getData();

    /**
     * @param array $payload
     */
    function setData(array $payload);

    /**
     * @return bool
     */
    function isActive();

    /**
     * @return bool
     */
    function isHandled();

    /**
     * Tag event as handled.
     */
    function handle();
}
