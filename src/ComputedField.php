<?php

namespace Drupal\virtual_edit_link_field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\user\Entity\User;


class ComputedField extends FieldItemList implements FieldItemListInterface {

  use ComputedItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {

    $admin = User::load(1);

    $now_timestamp = time();

    // lets check if admin user has logged in in the last day
    // access requires login. Could be just an open browser tab
    $last_accessed_date_timestamp = (int) $admin->getLastAccessedTime();
    $secs_since_last_accessed = $now_timestamp - $last_accessed_date_timestamp;

    if ($admin->hasPermission('Access Edit Link Field') && $secs_since_last_accessed <= (60 * 60 * 24)) {
      $options = [
        'absolute' => TRUE,
        'language' => \Drupal::languageManager()->getCurrentLanguage(),
      ];
      $edit_node = $this->getEntity();
      $editurl = $edit_node->toUrl('edit-form', $options)->toString();
      $this->list[0] = $this->createItem(0, $editurl);
    }
  }
  
}