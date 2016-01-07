<?php
namespace Weew\Eventer;

interface IEventSubscription {
    /**
     * @return int
     */
    function getId();

    /**
     * @return string
     */
    function getEventName();

    /**
     * @param $eventName
     */
    function setEventName($eventName);

    /**
     * @return mixed
     */
    function getSubscriber();

    /**
     * @param $subscriber
     */
    function setSubscriber($subscriber);
}
