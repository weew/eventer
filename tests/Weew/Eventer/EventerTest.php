<?php

namespace Tests\Weew\Eventer;

use PHPUnit_Framework_TestCase;
use Exception;
use Tests\Weew\Eventer\Stubs\AnotherSubscriber;
use Tests\Weew\Eventer\Stubs\CustomEvent;
use Tests\Weew\Eventer\Stubs\CustomSubscriber;
use Weew\Eventer\GenericEvent;
use Weew\Eventer\Eventer;
use Weew\Eventer\IEvent;
use Weew\Eventer\Invokers\CallableInvoker;
use Weew\Eventer\IEventSubscription;

class EventerTest extends PHPUnit_Framework_TestCase {
    public function test_create() {
        $eventer = new Eventer();
        $this->assertEquals([], $eventer->getSubscriptions());
    }

    public function test_subscribe_and_unsubscribe() {
        $eventer = new Eventer();
        $eventer->subscribe('foo', 'foo');
        $subscription = $eventer->subscribe('foo', 'bar');
        $eventer->subscribe('foo', 'baz');

        $this->assertTrue($subscription instanceof IEventSubscription);
        $this->assertEquals(3, count($eventer->getSubscriptions()));
        $this->assertEquals('foo', $eventer->getSubscriptions()[0]->getSubscriber());
        $this->assertEquals('bar', $eventer->getSubscriptions()[1]->getSubscriber());
        $this->assertEquals('baz', $eventer->getSubscriptions()[2]->getSubscriber());

        $this->assertTrue(
            $eventer->getSubscriptions()[0]->getId() != $eventer->getSubscriptions()[1]->getId()
        );
        $this->assertTrue(
            $eventer->getSubscriptions()[0]->getId() != $eventer->getSubscriptions()[2]->getId()
        );
        $this->assertTrue(
            $eventer->getSubscriptions()[1]->getId() != $eventer->getSubscriptions()[2]->getId()
        );

        $eventer->unsubscribe($subscription);
        $this->assertEquals(3, count($eventer->getSubscriptions()));
        $this->assertNull($eventer->getSubscriptions()[1]);
    }

    public function test_get_and_set_subscriptions() {
        $eventer = new Eventer();
        $this->assertEquals([], $eventer->getSubscriptions());
        $eventer->setSubscriptions(['bar']);
        $this->assertEquals(['bar'], $eventer->getSubscriptions());
    }

    public function test_get_and_set_subscription_invokers() {
        $eventer = new Eventer([]);
        $eventer->subscribe('foo', function () {
        });
        $event = new GenericEvent('foo');

        $this->assertEquals([], $eventer->getSubscriptionInvokers());
        $ex = null;
        try {
            $eventer->dispatch($event);
        } catch (Exception $e) {
            $ex = $e;
        }
        $this->assertNotNull($ex);
        $eventer->addSubscriptionInvoker(new CallableInvoker());
        $eventer->dispatch($event);
        $eventer->setSubscriptionInvokers([new CallableInvoker()]);
        $eventer->dispatch($event);
    }

    public function test_dispatch() {
        $shared = [];
        $eventer = new Eventer();

        $eventer->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 1;
        });

        $eventer->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 2;
        });

        $eventer->subscribe('bar', function (IEvent $event) use (&$shared) {
            $shared[] = $event->get('shared');
        });

        $eventer->dispatch(new GenericEvent('foo'));
        $this->assertEquals([1, 2], $shared);

        $eventer->dispatch(new GenericEvent('bar', ['shared' => 3]));
        $this->assertEquals([1, 2, 3], $shared);

        $eventer->dispatch('foo');
        $this->assertEquals([1, 2, 3, 1, 2], $shared);
    }

    public function test_dispatch_and_handle_event() {
        $shared = [];
        $eventer = new Eventer();

        $eventer->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 1;
        });

        $eventer->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 2;
            $event->handle();
        });

        $eventer->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 3;
        });

        $eventer->dispatch(new GenericEvent('foo'));
        $this->assertEquals([1, 2], $shared);
    }

    public function test_dispatch_custom_event() {
        $shared = [];
        $eventer = new Eventer();

        $eventer->subscribe(CustomEvent::class, function (CustomEvent $event) use (&$shared) {
            $shared[] = $event->getFoo();
        });

        $eventer->dispatch(new CustomEvent());
        $this->assertEquals(['foo'], $shared);
    }

    public function test_dispatch_returns_event() {
        $eventer = new Eventer();
        $event = new GenericEvent('foo');
        $this->assertTrue($eventer->dispatch($event) === $event);
    }

    public function test_dispatch_returns_handled_event_event() {
        $eventer = new Eventer();
        $event = new GenericEvent('foo');

        $eventer->subscribe($event->getName(), function(IEvent $event) {
            $event->handle();
        });

        $this->assertTrue($eventer->dispatch($event) === $event);
    }

    public function test_invoke_subscriber() {
        $shared = [];
        $eventer = new Eventer();
        $eventer->subscribe(CustomEvent::class, function (CustomEvent $event) use (&$shared) {
            $shared[] = 1;
        });
        $eventer->dispatch(new CustomEvent());
        $this->assertEquals([1], $shared);
        $eventer->subscribe(CustomEvent::class, CustomSubscriber::class);
        $event = new CustomEvent();
        $event->setShared($shared);
        $eventer->dispatch($event);
        $this->assertEquals([1, 1, 2], $shared);
    }
}
