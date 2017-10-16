<?php

/**
 * @file Contains a block that allows you to display the current node in a given
 *  display mode. Which makes it a lot easier for content editors to handle the
 *  different zones in a single form (node/%/edit) and use that input in various
 *  block displays.
 */

namespace Drupal\baseline\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'NodeDisplayBlock' block.
 *
 * @Block(
 *  id = "node_display_block",
 *  admin_label = @Translation("Node display block"),
 * )
 */
class NodeDisplayBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'display_mode' => 'full',
    ] + parent::defaultConfiguration();
  }

  /**
   * Add Cache Tags based on the node ID.
   * @return array|\string[]
   */
  public function getCacheTags() {
    //With this when your node change your block will rebuild
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      //if there is node add its cachetag
      return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
    } else {
      //Return default tags instead.
      return parent::getCacheTags();
    }
  }

  /**
   * Add Cache Context based on the route.
   * @return \string[]
   */
  public function getCacheContexts() {
    //if you depends on \Drupal::routeMatch()
    //you must set context of this block with 'route' context tag.
    //Every new route this block will rebuild
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Get all the node bundles as options
    $viewModeOptions = [];
    $viewModes = EntityViewMode::loadMultiple();

    /** @var EntityViewMode $viewMode */
    foreach($viewModes as $viewMode) {
      if($viewMode->getTargetType() == 'node') {
        $viewModeName = explode('.', $viewMode->id());
        $viewModeOptions[$viewModeName[1]] = $viewMode->label();
      }
    }

    $form['display_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('Display mode'),
      '#description' => $this->t('Which display mode should be used to display this node'),
      '#options' => $viewModeOptions,
      '#default_value' => $this->configuration['display_mode'],
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['display_mode'] = $form_state->getValue('display_mode');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node && !is_string($node)) {
      $display = EntityViewDisplay::load($node->getEntityTypeId() . '.' . $node->bundle() . '.' . $this->configuration['display_mode']);
      if ($display) {
        $build['node_block_display'] = node_view($node, $this->configuration['display_mode']);
      }
    }

    return $build;
  }

}
