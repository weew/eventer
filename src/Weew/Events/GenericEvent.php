<?php

namespace Weew\Events;

class GenericEvent extends Event {
    /**
     * @param $name
     * @param array $data
     */
    public function __construct($name = null, array $data = []) {
        $this->setName($name);
        $this->setData($data);
    }
}
