<?php

namespace Drupal\baseline\Plugin\Field\FieldFormatter;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\responsive_image\Entity\ResponsiveImageStyle;
use Drupal\responsive_image\Plugin\Field\FieldFormatter\ResponsiveImageFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Utility\LinkGeneratorInterface;

/**
 * Plugin for showing a single responsive image.
 *
 * @FieldFormatter(
 *   id = "single_responsive_image",
 *   label = @Translation("Single Responsive image"),
 *   field_types = {
 *     "image",
 *   },
 *   quickedit = {
 *     "editor" = "image"
 *   }
 * )
 */
class SingleResponsiveImageFormatter extends ResponsiveImageFormatter {

  /**
   * @inheritdoc
   */
  public function getEntitiesToView(EntityReferenceFieldItemListInterface $items, $langcode) {
    $entities = parent::getEntitiesToView($items, $langcode);
    if (!empty($entities) && count($entities) > 0) {
      return array_slice($entities, 0, 1, TRUE);
    }

    return $entities;
  }

}
