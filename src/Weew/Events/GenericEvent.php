<?php

namespace Weew\Events;

class GenericEvent extends Event {
    /**
     * @param $topic
     * @param array $data
     */
    public function __construct($topic = null, array $data = []) {
        $this->setTopic($topic);
        $this->setData($data);
    }
}
