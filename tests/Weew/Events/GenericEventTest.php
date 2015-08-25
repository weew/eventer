<?php

namespace Tests\Weew\Events;

use PHPUnit_Framework_TestCase;
use Weew\Events\GenericEvent;

class GenericEventTest extends PHPUnit_Framework_TestCase {
    public function test_create_with_name() {
        $event = new GenericEvent('foo');
        $this->assertEquals('foo', $event->getName());
    }

    public function test_create_with_data() {
        $event = new GenericEvent('foo', ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $event->getData());
    }
}
