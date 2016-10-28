<?php

namespace Drupal\uptime_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block
 *
 * @Block(
 *   id = "uptimewidget_block",
 *   admin_label = @Translation("Uptime Widget"),
 * )
 */
class UptimeWidgetBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
  return array(
      '#type' => 'markup',
      '#markup' => 'Hello This block list the article.',
    );
  }
}
