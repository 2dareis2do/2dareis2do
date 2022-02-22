<?php

namespace Drupal\virtual_edit_link_field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\user\Entity\User;

class ComputedField extends FieldItemList implements FieldItemListInterface {

  use ComputedItemListTrait;
  
  /**
   * Compute the values.
   */
  protected function computeValue() {
    
    // lets check if admin user has logged in in the last hour
    $admin = User::load(1);
    $now_timestamp = time();
    $last_login_date_timestamp = (int) $admin->getLastAccessedTime();
    $secs_since_last_logged_in = $now_timestamp - $last_login_date_timestamp;

    if ($admin->hasPermission('Access Edit Link Field') && $secs_since_last_logged_in <= (60 * 60 * 24)) {
      $edit_node_id = $this->parent->getEntity()->id();
      $edit_entity_type_bundle = $this->parent->getEntity()->getEntityTypeId();
      $edit_base_url = \Drupal::request()->getSchemeAndHttpHost();
      $editurl =  $edit_base_url . "/" . $edit_entity_type_bundle . "/" . $edit_node_id ."/edit/";
      $this->list[0] = $this->createItem(0, $editurl);
    }
  }
  
}