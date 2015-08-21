<?php

namespace Tests\Weew\Events\Invokers;

use PHPUnit_Framework_TestCase;
use Weew\Events\Event;
use Weew\Events\Invokers\CallableInvoker;
use Weew\Events\Subscription;

class CallableInvokerTest extends PHPUnit_Framework_TestCase {
    public function test_invoke() {
        $shared = [];
        $invoker = new CallableInvoker();
        $subscription = new Subscription(1, 'foo', function() use (&$shared) {
            $shared[] = 1;
        });

        $this->assertFalse($invoker->invoke(new Subscription(1, 'foo', 'bla'), new Event()));
        $this->assertEquals([], $shared);
        $this->assertTrue($invoker->invoke($subscription, new Event()));
        $this->assertEquals([1], $shared);
    }
}
