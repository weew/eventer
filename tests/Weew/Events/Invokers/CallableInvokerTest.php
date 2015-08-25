<?php

namespace Tests\Weew\Events\Invokers;

use PHPUnit_Framework_TestCase;
use Weew\Events\GenericEvent;
use Weew\Events\Invokers\CallableInvoker;
use Weew\Events\EventSubscription;

class CallableInvokerTest extends PHPUnit_Framework_TestCase {
    public function test_invoke() {
        $shared = [];
        $invoker = new CallableInvoker();
        $subscription = new EventSubscription(1, 'foo', function() use (&$shared) {
            $shared[] = 1;
        });

        $this->assertFalse($invoker->invoke(new EventSubscription(1, 'foo', 'bla'), new GenericEvent()));
        $this->assertEquals([], $shared);
        $this->assertTrue($invoker->invoke($subscription, new GenericEvent()));
        $this->assertEquals([1], $shared);
    }
}
