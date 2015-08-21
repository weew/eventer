<?php

namespace Tests\Weew\Events\Invokers;

use PHPUnit_Framework_TestCase;
use Tests\Weew\Events\Stubs\CustomEvent;
use Tests\Weew\Events\Stubs\CustomSubscriber;
use Weew\Events\Invokers\SubscriberInvoker;
use Weew\Events\Subscription;

class SubscriberInvokerTest extends PHPUnit_Framework_TestCase {
    public function test_invoke() {
        $shared = [];
        $invoker = new SubscriberInvoker();
        $event = new CustomEvent();
        $event->setShared($shared);

        $this->assertFalse($invoker->invoke(new Subscription(1, 'foo', 'bla'), $event));
        $this->assertEquals([], $shared);
        $this->assertTrue($invoker->invoke(new Subscription(1, 'foo', CustomSubscriber::class), $event));
        $this->assertEquals([2], $shared);
        $this->assertTrue($invoker->invoke(new Subscription(1, 'foo', new CustomSubscriber()), $event));
        $this->assertEquals([2, 2], $shared);
    }
}
