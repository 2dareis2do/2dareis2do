<?php

/**
 * @file
 * Contains virtual_edit_link_field\virtual_edit_link_field.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */


/**
* Implements hook_views_data().
*/
function virtual_edit_link_field_views_data() {

    $data['views']['table']['group'] = t('Custom Global');
    $data['views']['table']['join'] = [
      // #global is a special flag which allows a table to appear all the time.
      '#global' => [],
    ];

    $data['views']['virtual_edit_link_views'] = [
        'title' => t('Virtual edit link views'),
        'help' => t('My awesome custom views field plugin.'),
        'field' => [
            'id' => 'virtual_edit_link_views',
        ],
    ];
    return $data;
}
