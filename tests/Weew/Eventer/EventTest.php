<?php

namespace Tests\Weew\Eventer;

use PHPUnit_Framework_TestCase;
use Weew\Eventer\GenericEvent;

class EventTest extends PHPUnit_Framework_TestCase {
    public function test_get_and_set_name() {
        $event = new GenericEvent();
        $this->assertEquals(get_class($event), $event->getName());
        $event->setName('bar');
        $this->assertEquals('bar', $event->getName());
    }

    public function test_status() {
        $event = new GenericEvent();
        $this->assertTrue($event->isActive());
        $this->assertFalse($event->isHandled());
        $event->handle();
        $this->assertFalse($event->isActive());
        $this->assertTrue($event->isHandled());
    }

    public function test_get_and_set_data() {
        $event = new GenericEvent();
        $event->setData(['bar' => 'foo']);
        $this->assertEquals(['bar' => 'foo'], $event->getData());
        $this->assertEquals('foo', $event->get('bar'));
        $this->assertTrue($event->has('bar'));
        $event->remove('bar');
        $this->assertFalse($event->has('bar'));
        $this->assertNull($event->get('bar'));
        $this->assertNull($event->get('foo'));
        $event->set('foo', 'bar');
        $this->assertEquals('bar', $event->get('foo'));
        $this->assertEquals(['foo' => 'bar'], $event->getData());
    }
}
