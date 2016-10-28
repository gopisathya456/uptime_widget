<?php

namespace Drupal\uptime_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'UptimeWidgetBlock' Block
 *
 * @Block(
 *   id = "uptime_widget_block",
 *   admin_label = @Translation("Uptime Widget"),
 * )
 */
class UptimeWidgetBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    return array(
      '#theme' => 'uptime_widget_block',
      '#attached' => array(
        'library' => array(
          'uptime_widget/uptime_widget'
        ),
      ),
    );
  }

}
