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
   
   $config = $this->config('uptime_widget.admin_config_uptime_widget');
    $last_refresh = $config->get('next_execution') - $config->get('interval');
    // Execution time has to be reset to force an instant cron run.
    //$config->set('next_execution', 0);
    // To find a cron call here looks odd, but it's the only way to have any
    // changed variables in the form being processed in the hook_cron(). After
    // submitting the form you come back on the same form and that's when all
    // new variables are available. The only drawback is that cron runs twice
    // (once at the first form load and once at the second), but that's not a
    // big deal.
    //drupal_cron_run();
    // Essential to have some credentials.
    $api_key = trim($config->get('api_key'));
    $monitor_id = trim($config->get('monitor_id'));
    // Where to find the all-time uptime ratio.
    $url = "http://api.uptimerobot.com/getMonitors?apiKey=" . $api_key . "&monitors=" . $monitor_id . "&format=xml";

    $form['api_settings'] = array(
      '#type' => 'fieldset',
      '#title' => t('Uptime'),
    );

    $form['api_settings']['uptime_enabled'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enabled'),
      '#default_value' => $config->get('enabled'),
      '#description' => t('Disabling pauses the monitor until re-enabling and removes the ratio display. Disable uptime when your site might go down temporarily,for example during development, or if you want to use only the copyright notice.'),
    );

    $form['api_settings']['uptime_api_key'] = array(
      '#type' => 'textfield',
      '#title' => t('API key'),
      '#default_value' => $api_key,
      '#description' => t('this is description'),
      '#maxlength' => 40,
      '#required' => TRUE,
    );

    $form['api_settings']['uptime_monitor_id'] = array(
     '#type' => 'textfield',
      '#title' => t('Monitor ID'),
      '#default_value' => $monitor_id,
      '#description' => t('To find your Monitor ID go to'),
      '#size' => 10,
      '#maxlength' => 10,
      '#required' => TRUE,
    );

    // Grabbing the uptime ratio once a day is good enough, but leave it up to
    // the site owner to decide. Second option is the actual set cron interval.
    $form['api_settings']['uptime_interval'] = array(
      '#type' => 'radios',
      '#title' => t('Refresh interval'),
      '#options' => array(
        86400 => t('24 hours (recommended)'),
        0 => t('(every cron run)'),
        ),
      '#default_value' => $config->get('interval'),
      '#description' => t('Saving'),
      '#required' => TRUE,
    );

    // Offering the possibility to check the source of the data.
    $form['api_settings']['raw check'] = array(
      '#type' => 'fieldset',
      '#title' => t('Data check'),
      '#description' => t('Once you')
        );

    $form['uptime_notice'] = array(
      '#type' => 'fieldset',
      '#title' => t('Copyright notice'),
    );

    $form['uptime_notice']['uptime_notice_enabled'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enabled'),
      '#default_value' => $config->get('notice_enabled'),
    );

    // For the examples we use real data.
    // Current domain name without the leading protocol.
    global $base_url;
    $year = $config->get('year');
    $notice = $config->get('prepend') . ' © ' . (($year != date('Y') && !empty($year)) ? $year . '-' . date('Y') : date('Y'));
    $site_config = $this->config('system.site');
    $form['uptime_notice']['uptime_url_name'] = array(
      // Create different types of notices to choose from.
      '#type' => 'radios',
      '#title' => t('Choose a notice'),
      '#options' => array(
        $base_url => '<strong>' . $notice . ' ' . $base_url . '</strong> ' . t('(Base url. Default.)'),
        $site_config->get('name') => '<strong>' . $notice . ' ' . $site_config->get('name') . '</strong> ' . t("(Site name. Preferable if the site name is a person's full name or a company name.)"),
      ' ' => '<strong>' . $notice . '</strong> ' . t('(Leaving out the designation of owner is not recommended.)'),
      ),
      '#default_value' => $config->get('url_name'),
      '#description' => t("'Year of first publication' is not used until entered below, for example © 2009-") . date('Y') . '. ' . t('Save this form to refresh above examples.'),
    );

    $form['uptime_notice']['uptime_year'] = array(
      '#type' => 'textfield',
      '#title' => t('What year was the domain first online?'),
      '#default_value' => $year,
      '#description' => t("Leave empty to display only the current year (default). Also if the 'starting year' equals the 'current year' only one will be displayed until next year.<br />To play safe legally, it's best to enter a 'Year of first publication', although copyright is in force even without any notice."),
      '#size' => 4,
      '#maxlength' => 4,
    );
  
    $form['uptime_notice']['uptime_prepend'] = array(
      '#type' => 'textfield',
      '#title' => t('Prepend text'),
      '#default_value' => trim($config->get('prepend')),
      '#description' => t("For example 'All images' on a photographer's website."),
    );
   
   return parent::buildForm($form, $form_state);
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $config = $this->config('config.admin_config_uptime_widget');
    
      $config->set('enabled', $form_state->getValue('uptime_enabled'));
      $config->set('api_key', $form_state->getValue('uptime_api_key'));
      $config->set('monitor_id', $form_state->getValue('uptime_monitor_id'));
      $config->set('prepend', $form_state->getValue('uptime_prepend'));
      $config->set('interval', $form_state->getValue('uptime_interval'));
      $config->set('notice_enabled', $form_state->getValue('uptime_notice_enabled'));
      $config->set('year', $form_state->getValue('uptime_year'));
      $config->set('prepend', $form_state->getValue('uptime_prepend'));
      $config->set('url_name', $form_state->getValue('uptime_url_name'));
      $config->save();

    parent::submitForm($form, $form_state);
  }
  
}
