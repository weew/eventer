# Simple event system

[![Build Status](https://img.shields.io/travis/weew/php-eventer.svg)](https://travis-ci.org/weew/php-eventer)
[![Code Quality](https://img.shields.io/scrutinizer/g/weew/php-eventer.svg)](https://scrutinizer-ci.com/g/weew/php-eventer)
[![Test Coverage](https://img.shields.io/coveralls/weew/php-eventer.svg)](https://coveralls.io/github/weew/php-eventer)
[![Version](https://img.shields.io/packagist/v/weew/php-eventer.svg)](https://packagist.org/packages/weew/php-eventer)
[![Licence](https://img.shields.io/packagist/l/weew/php-eventer.svg)](https://packagist.org/packages/weew/php-eventer)

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

`composer install weew/php-eventer`

## Usage

This event system allows you to easily subscribe to certain events and get
notified as soon as one occurs. The most simple way to subscribe to an event
is by using a string as the event name.

### Simple subscription

The easiest way to create a subscription is to use callback function.

```php
$eventer = new Eventer();
$eventer->subscribe('event.name', function(IEvent $event) {
    echo $event->getName();
    // event.name
});
$eventer->dispatch('event.name');
```

### Attaching data to an event

Mostly you want to throw an event with particular data attached. The quickest way
to achieve this is to use the generic events.

```php
$eventer = new Eventer();
$eventer->subscribe('event.name', function(IEvent $event) {
    var_dump($event->getData());
    // ['secret' => 'secret value']
    echo $event->get('secret');
    // secret value
});

$event = new GenericEvent('event.name', ['secret' => 'secret value']);
// or
$event = new GenericEvent('event.name');
$event->set('secret', 'secret value');

$eventer->dispatch($event);
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

$eventer = new Eventer();
$eventer->subscribe(CustomEvent::class, function(CustomEvent $event) {
    echo $event->getSecret();
    // secret value
});

$eventer->dispatch(new CustomEvent());
```

### Unsubscribing from events

To unsubscribe from an event you can simply pass the subscription object
to the unsubscribe method on the event dispatcher.

```php
$eventer = new Eventer();
$subscription = $eventer->subscribe('event.name', 'abstract.value');
$eventer->unsubscribe($subscription);
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

$eventer = new Eventer();
$eventer->subscribe(CustomEvent::class, CustomEventSubscriber::class);
$eventer->dispatch(new CustomEvent());
```

## Existing container integrations

There is an integration for the [weew/php-container](https://github.com/weew/php-container). See [php-eventer-container-aware](https://github.com/weew/php-eventer-container-aware).
