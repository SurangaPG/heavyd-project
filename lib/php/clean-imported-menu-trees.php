<?php

/**
 * Very minimal helper to ensure the menu tree structure after importing.
 *
 * @TODO This is absolute minimal MVP and should be ported to a decent phing task at some point.
 */

$menuContentLinks = \Drupal\menu_link_content\Entity\MenuLinkContent::loadMultiple();

foreach ($menuContentLinks as $menuContentLink) {
  $menuContentLink->save();
}
