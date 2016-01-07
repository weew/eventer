<?php

namespace Tests\Weew\Eventer\Invokers;

use PHPUnit_Framework_TestCase;
use Tests\Weew\Eventer\Stubs\CustomEvent;
use Tests\Weew\Eventer\Stubs\CustomSubscriber;
use Weew\Eventer\Invokers\SubscriberInvoker;
use Weew\Eventer\EventSubscription;

class SubscriberInvokerTest extends PHPUnit_Framework_TestCase {
    public function test_invoke() {
        $shared = [];
        $invoker = new SubscriberInvoker();
        $event = new CustomEvent();
        $event->setShared($shared);

        $this->assertFalse($invoker->invoke(new EventSubscription(1, 'foo', 'bla'), $event));
        $this->assertEquals([], $shared);
        $this->assertTrue($invoker->invoke(new EventSubscription(1, 'foo', CustomSubscriber::class), $event));
        $this->assertEquals([2], $shared);
        $this->assertTrue($invoker->invoke(new EventSubscription(1, 'foo', new CustomSubscriber()), $event));
        $this->assertEquals([2, 2], $shared);
    }
}
