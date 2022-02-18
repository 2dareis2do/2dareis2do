<?php

namespace Drupal\virtual_edit_link_field;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\Core\TypedData\TypedData;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Render\FilteredMarkup;

/**
 * A computed property for processing text with a format.
 *
 * Required settings (below the definition's 'settings' key) are:
 *  - text source: The text property containing the to be processed text.
 */
class VirtualEditLinkFieldProcessed extends TypedData implements CacheableDependencyInterface {

  /**
   * Cached processed text.
   *
   * @var \Drupal\filter\FilterProcessResult|null
   */
  protected $processed = NULL;

  /**
   * {@inheritdoc}
   */
  public function __construct(DataDefinitionInterface $definition, $name = NULL, TypedDataInterface $parent = NULL) {
    parent::__construct($definition, $name, $parent);

    if ($definition->getSetting('text source') === NULL) {
      throw new \InvalidArgumentException("The definition's 'text source' key has to specify the name of the text property to be processed.");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    if ($this->processed !== NULL) {  
      return FilteredMarkup::create($this->processed->getProcessedText());
    }

    $item = $this->getParent();
    
    $nid = $item->parent->parent->getEntity()->id();
    $type = $item->parent->parent->getEntity()->getEntityTypeId();
    // $label = $item->parent->parent->getEntity()->label();
    $editurl = \Drupal::request()->getSchemeAndHttpHost() . $type . $nid ."/edit/";
    $computed_value = $editurl;

    // get entity 
    // to do get node id from parent 
    
    $text = $item->{($this->definition->getSetting('text source'))};

    // Avoid doing unnecessary work on empty strings.
    if (!isset($text) || $text === '') {
      $this->processed = new FilterProcessResult($nid);
    }
    else {
      

      $build = [
        '#title' => $this->t('Examples'),
        '#type' => 'link',
        '#url' => $editurl,
        // '#type' => 'label',
        // '#text' => $url,
        // '#format' => $item->format,
        // '#filter_types_to_skip' => [],
        // '#langcode' => $item->getLangcode(),
      ];
      // Capture the cacheability metadata associated with the processed text.
      $processed_text = $this->getRenderer()->renderPlain($build);
      // $test = $this->getRenderer()->renderPlaceholder($url,$build);
      $this->processed = FilterProcessResult::createFromRenderArray($build)->setProcessedText((string) $computed_value);
    }
    return FilteredMarkup::create($this->processed->getProcessedText());
    // return "test1ng";
    // return $computed_value;
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($value, $notify = TRUE) {
    // if ($value === null) {
      // $nid =  $this->parent->parent->parent->getEntity()->id();
      // $type = $this->parent->parent->parent->getEntity()->getEntityTypeId();
      // $editurl = \Drupal::request()->getSchemeAndHttpHost() . $type . $nid ."/edit/";
      // $this->processed = $editurl;
    // } else {
      $this->processed = $value;
    // }
    // Notify the parent of any changes.
    if ($notify && isset($this->parent)) {
      $this->parent->onChange($this->name);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $this->getValue();
    return $this->processed->getCacheTags();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $this->getValue();
    return $this->processed->getCacheContexts();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    $this->getValue();
    return $this->processed->getCacheMaxAge();
  }

  /**
   * {@inheritdoc}
   */
  public function applyDefaultValue($notify = TRUE) {
    // Set to url of edit mode
    $nid =  $this->parent->parent->parent->getEntity()->id();
    $type = $this->parent->parent->parent->getEntity()->getEntityTypeId();
    $editurl = \Drupal::request()->getSchemeAndHttpHost() . $type . "/" . $nid ."/edit/";
    // $this->processed = $editurl;
    $this->setValue($editurl, $notify);
    return $this;
  }
  
  /**
   * Returns the renderer service.
   *
   * @return \Drupal\Core\Render\RendererInterface
   */
  protected function getRenderer() {
    return \Drupal::service('renderer');
  }

}
