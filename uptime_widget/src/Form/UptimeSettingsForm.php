<?php

/**
 * @file
 * Contains \Drupal\uptime_widget\Form\UptimeSettingsForm.
 */

namespace Drupal\uptime_widget\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

class UptimeSettingsForm extends ConfigFormBase {
  
  /**
 * Constructor for ComproCustomForm.
 *
 * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
 * The factory for configuration objects.
 */
 public function __construct(ConfigFactoryInterface $config_factory) {
   parent::__construct($config_factory);
 }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'uptime_widget_admin_settings';
  }
  
  /**
 * Gets the configuration names that will be editable.
 *
 * @return array
 * An array of configuration object names that are editable if called in
 * conjunction with the trait's config() method.
 */
 protected function getEditableConfigNames() {
   return ['config.admin_config_uptime_widget'];
 }
 

  /**
   * {@inheritdoc}
   */
 public function buildForm(array $form, FormStateInterface $form_state) {
   $compro_custom = $this->config('config.admin_config_uptime_widget');
   $site_name = $this->config('system.site')->get('name');
 
   // Logo settings for theme override.
   $form['compro_custom']['logo'] = array(
     'title' => array(
       '#type' => 'textfield',
       '#title' => t('Title text'),
       '#maxlength' => 255,
       '#default_value' => $compro_custom->get('logo_title') ? $compro_custom->get('logo_title') : $site_name . ' home',
       '#description' => t('What the tooltip should say when you hover on the logo.'),
     ),
   );
   return parent::buildForm($form, $form_state);
  }
  
}

