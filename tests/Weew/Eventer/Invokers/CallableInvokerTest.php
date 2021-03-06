<?php

namespace Tests\Weew\Eventer\Invokers;

use PHPUnit_Framework_TestCase;
use Weew\Eventer\GenericEvent;
use Weew\Eventer\Invokers\CallableInvoker;
use Weew\Eventer\EventSubscription;

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
