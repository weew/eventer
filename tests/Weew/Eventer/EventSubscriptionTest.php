<?php

namespace Tests\Weew\Eventer;

use PHPUnit_Framework_TestCase;
use Weew\Eventer\EventSubscription;

class EventSubscriptionTest extends PHPUnit_Framework_TestCase {
    public function test_getters_and_setters() {
        $sub = new EventSubscription('foo', 'bar', 'ba');
        $this->assertEquals('foo', $sub->getId());
        $this->assertEquals('bar', $sub->getEventName());
        $this->assertEquals('ba', $sub->getSubscriber());
        $sub->setEventName('yolo');
        $sub->setSubscriber('swag');
        $this->assertEquals('yolo', $sub->getEventName());
        $this->assertEquals('swag', $sub->getSubscriber());
    }
}
