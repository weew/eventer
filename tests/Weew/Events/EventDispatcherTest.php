<?php

namespace Tests\Weew\Events;

use PHPUnit_Framework_TestCase;
use Exception;
use Tests\Weew\Events\Stubs\AnotherSubscriber;
use Tests\Weew\Events\Stubs\CustomEvent;
use Tests\Weew\Events\Stubs\CustomSubscriber;
use Weew\Events\GenericEvent;
use Weew\Events\EventDispatcher;
use Weew\Events\IEvent;
use Weew\Events\Invokers\CallableInvoker;
use Weew\Events\IEventSubscription;

class EventDispatcherTest extends PHPUnit_Framework_TestCase {
    public function test_create() {
        $dispatcher = new EventDispatcher();
        $this->assertEquals([], $dispatcher->getSubscriptions());
    }

    public function test_subscribe_and_unsubscribe() {
        $dispatcher = new EventDispatcher();
        $dispatcher->subscribe('foo', 'foo');
        $subscription = $dispatcher->subscribe('foo', 'bar');
        $dispatcher->subscribe('foo', 'baz');

        $this->assertTrue($subscription instanceof IEventSubscription);
        $this->assertEquals(3, count($dispatcher->getSubscriptions()));
        $this->assertEquals('foo', $dispatcher->getSubscriptions()[0]->getSubscriber());
        $this->assertEquals('bar', $dispatcher->getSubscriptions()[1]->getSubscriber());
        $this->assertEquals('baz', $dispatcher->getSubscriptions()[2]->getSubscriber());

        $this->assertTrue(
            $dispatcher->getSubscriptions()[0]->getId() != $dispatcher->getSubscriptions()[1]->getId()
        );
        $this->assertTrue(
            $dispatcher->getSubscriptions()[0]->getId() != $dispatcher->getSubscriptions()[2]->getId()
        );
        $this->assertTrue(
            $dispatcher->getSubscriptions()[1]->getId() != $dispatcher->getSubscriptions()[2]->getId()
        );

        $dispatcher->unsubscribe($subscription);
        $this->assertEquals(3, count($dispatcher->getSubscriptions()));
        $this->assertNull($dispatcher->getSubscriptions()[1]);
    }

    public function test_get_and_set_subscriptions() {
        $dispatcher = new EventDispatcher();
        $this->assertEquals([], $dispatcher->getSubscriptions());
        $dispatcher->setSubscriptions(['bar']);
        $this->assertEquals(['bar'], $dispatcher->getSubscriptions());
    }

    public function test_get_and_set_subscription_invokers() {
        $dispatcher = new EventDispatcher([]);
        $dispatcher->subscribe('foo', function () {
        });
        $event = new GenericEvent('foo');

        $this->assertEquals([], $dispatcher->getSubscriptionInvokers());
        $ex = null;
        try {
            $dispatcher->dispatch($event);
        } catch (Exception $e) {
            $ex = $e;
        }
        $this->assertNotNull($ex);
        $dispatcher->addSubscriptionInvoker(new CallableInvoker());
        $dispatcher->dispatch($event);
        $dispatcher->setSubscriptionInvokers([new CallableInvoker()]);
        $dispatcher->dispatch($event);
    }

    public function test_dispatch() {
        $shared = [];
        $dispatcher = new EventDispatcher();

        $dispatcher->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 1;
        });

        $dispatcher->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 2;
        });

        $dispatcher->subscribe('bar', function (IEvent $event) use (&$shared) {
            $shared[] = $event->get('shared');
        });

        $dispatcher->dispatch(new GenericEvent('foo'));
        $this->assertEquals([1, 2], $shared);

        $dispatcher->dispatch(new GenericEvent('bar', ['shared' => 3]));
        $this->assertEquals([1, 2, 3], $shared);

        $dispatcher->dispatch('foo');
        $this->assertEquals([1, 2, 3, 1, 2], $shared);
    }

    public function test_dispatch_and_handle_event() {
        $shared = [];
        $dispatcher = new EventDispatcher();

        $dispatcher->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 1;
        });

        $dispatcher->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 2;
            $event->handle();
        });

        $dispatcher->subscribe('foo', function (IEvent $event) use (&$shared) {
            $shared[] = 3;
        });

        $dispatcher->dispatch(new GenericEvent('foo'));
        $this->assertEquals([1, 2], $shared);
    }

    public function test_dispatch_custom_event() {
        $shared = [];
        $dispatcher = new EventDispatcher();

        $dispatcher->subscribe(CustomEvent::class, function (CustomEvent $event) use (&$shared) {
            $shared[] = $event->getFoo();
        });

        $dispatcher->dispatch(new CustomEvent());
        $this->assertEquals(['foo'], $shared);
    }

    public function test_invoke_subscriber() {
        $shared = [];
        $dispatcher = new EventDispatcher();
        $dispatcher->subscribe(CustomEvent::class, function (CustomEvent $event) use (&$shared) {
            $shared[] = 1;
        });
        $dispatcher->dispatch(new CustomEvent());
        $this->assertEquals([1], $shared);
        $dispatcher->subscribe(CustomEvent::class, CustomSubscriber::class);
        $event = new CustomEvent();
        $event->setShared($shared);
        $dispatcher->dispatch($event);
        $this->assertEquals([1, 1, 2], $shared);
    }
}
