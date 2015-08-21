<?php

namespace Tests\Weew\Events\Stubs;

use Weew\Events\Event;

class CustomEvent extends Event {
    private $shared;

    public function getFoo() {
        return 'foo';
    }

    public function setShared(array &$shared) {
        $this->shared = &$shared;
    }

    public function &getShared() {
        return $this->shared;
    }
}
