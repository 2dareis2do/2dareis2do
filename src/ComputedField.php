<?php

namespace Drupal\virtual_edit_link_field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\ComputedItemListTrait;

class ComputedField extends FieldItemList implements FieldItemListInterface {

  use ComputedItemListTrait;
  
  /**
   * Compute the values.
   */
  protected function computeValue() {
    $edit_node_id = $this->parent->getEntity()->id();
    $edit_entity_type_bundle = $this->parent->getEntity()->getEntityTypeId();
    $edit_base_url = \Drupal::request()->getSchemeAndHttpHost();
    $editurl =  $edit_base_url . "/" . $edit_entity_type_bundle . "/" . $edit_node_id ."/edit/";
    $this->list[0] = $this->createItem(0, $editurl);
  }
  
}