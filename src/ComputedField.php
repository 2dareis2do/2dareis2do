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
    // isAuthenticated() seems to be abailable even if we are not logged in?
    $admin = User::load(1);
    $nowtime = time();
    $last_login_date = (int) $admin->getLastAccessedTime();
    $last_logged_in = $nowtime - $last_login_date;

    if ($admin->isAuthenticated() && $admin->hasPermission('First level permission') && $last_logged_in <= (60 * 60)) {
      $edit_node_id = $this->parent->getEntity()->id();
      $edit_entity_type_bundle = $this->parent->getEntity()->getEntityTypeId();
      $edit_base_url = \Drupal::request()->getSchemeAndHttpHost();
      $editurl =  $edit_base_url . "/" . $edit_entity_type_bundle . "/" . $edit_node_id ."/edit/";
      $this->list[0] = $this->createItem(0, $editurl);
    }
  }
  
}