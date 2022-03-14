<?php

namespace Drupal\virtual_edit_link_field\EventSubscriber;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;

class SetExpiresSubscriber implements EventSubscriberInterface {

  /**
   * @var CacheTagsInvalidatorInterface
   */
  protected $invalidator;
  
  /**
   * @var FilterResponseEvent
   */
  public function onResponse(FilterResponseEvent $event) {
    $request = $event->getRequest();
    $string = "/api";
    $current = $request->getRequestUri();
    if (strpos($current, $string) !== false) { 
      $tags = $event->getResponse()->getCacheableMetadata()->getCacheTags();
      $this->invalidator->invalidateTags($tags);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onResponse'];
    return $events;
  }
  
  /**
   * SetExpiresSubscriber constructor.
   *
   * @param CacheTagsInvalidatorInterface $invalidator
   */
  public function __construct(CacheTagsInvalidatorInterface $invalidator)
  {
    $this->invalidator = $invalidator;
  }

}