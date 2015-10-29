# Simple event system

[![Build Status](https://travis-ci.org/weew/php-events.svg?branch=master)](https://travis-ci.org/weew/php-events)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/weew/php-events/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/weew/php-events/?branch=master)
[![Coverage Status](https://coveralls.io/repos/weew/php-events/badge.svg?branch=master&service=github)](https://coveralls.io/github/weew/php-events?branch=master)
[![License](https://poser.pugx.org/weew/php-events/license)](https://packagist.org/packages/weew/php-events)

## Table of contents

- [Installation](#installation)
- [Usage](#usage)
    - [Simple subscription](#simple-subscription)
    - [Attaching data to an event](#attaching-data-to-an-event)
    - [Creating custom events](#creating-custom-events)
    - [Unsubscribing from events](#unsubscribing-from-events)
    - [Event subscribers](#event-subscribers)
- [Existing container integrations](#existing-container-integrations)

## Installation

`composer install weew/php-events`

## Usage

The event system allows you to easily subscribe to certain events and get
notified as soon as one occurs. The most simple way to subscribe to an event
is by using a string as the event name.

### Simple subscription

The easiest way to create a subscription is to use callback function.

```php
$dispatcher = new EventDispatcher();
$dispatcher->subscribe('event.name', function(IEvent $event) {
    echo $event->getName();
    // event.name
});
$dispatcher->dispatch('event.name');
```

### Attaching data to an event

Mostly you want to throw an event with particular data attached. The quickest way
to achieve this is to use the generic events.

```php
$dispatcher = new EventDispatcher();
$dispatcher->subscribe('event.name', function(IEvent $event) {
    var_dump($event->getData());
    // ['secret' => 'secret value']
    echo $event->get('secret');
    // secret value
});

$event = new GenericEvent('event.name', ['secret' => 'secret value']);
// or
$event = new GenericEvent('event.name');
$event->set('secret', 'secret value');

$dispatcher->dispatch($event);
```

### Creating custom events

In more complex applications I would suggest to roll your own events. This makes
the code much more easier to understand since you'll never have to guess what
the event name might be. It also allows you to extend your events with more complex
behaviour.

```php
class CustomEvent extends Event {
    public function getSecret() {
        return 'secret value';
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->subscribe(CustomEvent::class, function(CustomEvent $event) {
    echo $event->getSecret();
    // secret value
});

$dispatcher->dispatch(new CustomEvent());
```

### Unsubscribing from events

To unsubscribe from an event you can simply pass the subscription object
to the unsubscribe method on the event dispatcher.

```php
$dispatcher = new EventDispatcher();
$subscription = $dispatcher->subscribe('event.name', 'abstract.value');
$dispatcher->unsubscribe($subscription);
```

### Event subscribers

Using callbacks in your events might not always be an optimal solution.
Therefore you can create event subscriber classes that get called whenever an event
occurs.

```php
class CustomEvent extends Event {
    public function getSecret() {
        return 'secret value';
    }
}

class CustomEventSubscriber implements IEventSubscriber {
    public function handle(IEvent $event) {
        /** @var CustomEvent $event */
        echo $event->getSecret();
        // secret value
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->subscribe(CustomEvent::class, CustomEventSubscriber::class);
$dispatcher->dispatch(new CustomEvent());
```

## Existing container integrations

There is an integration for the [weew/php-container](https://github.com/weew/php-container). See [php-events-container-aware](https://github.com/weew/php-events-container-aware).
