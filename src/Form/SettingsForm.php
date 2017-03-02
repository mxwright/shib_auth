<?php

/**
 * @file
 * Contains Drupal\shib_auth\Form\SettingsForm.
 */

namespace Drupal\shib_auth\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\shib_auth\Form
 */
class SettingsForm extends ConfigFormBase {
  
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'shib_auth.settings',
    ];
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'shib_auth_settings_form';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('shib_auth.settings');
     
    $form['basic'] = array(
      '#type' => 'fieldset',
      '#title' => t('Shibboleth Variable Settings'),
      '#collapsible' => True,
      '#tree' => FALSE,
    );
    $form['basic']['username_field'] = array(
      '#type' => 'textfield',
      '#title' => t('Username Variable'),
      '#description' => t('This is the environment variable from mod_shib that will be used to match to Drupal username'),
      '#default_value' => $this->config('shib_auth.settings')->get('username_field'),
      '#size' => 15,
      '#maxlength' => 256,
      '#required' => TRUE,
    );	
    $form['basic']['email_field'] = array(
      '#type' => 'textfield',
      '#title' => t('Email Variable'),
      '#description' => t('This is the environment variable from mod_shib that will be used to match email address'),
      '#default_value' => $this->config('shib_auth.settings')->get('email_field'),
      '#size' => 15,
      '#maxlength' => 256,
      '#required' => TRUE,
    );
    $form['basic']['login_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Login Path'),
      '#description' => t('This is the path to shibboleth for logins. The current domain and site path will be substituted for CURRENT_PATH'),
      '#default_value' => $this->config('shib_auth.settings')->get('login_link'),
      '#size' => 40,
      '#maxlength' => 256,
      '#required' => TRUE,
    );
    
    $form['other'] = array(
      '#type' => 'fieldset',
      '#title' => t('Other Settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
    
    
    $form['other']['autocreate_accounts'] = array(
      '#type' => 'checkbox',
      '#title' => 'Automatically create user accounts?',
      '#return_value' => 1,
      '#default_value' => $this->config('shib_auth.settings')->get('autocreate_accounts'),
      '#description' => t('If set to true, anyone with a valid Shibboleth login will have a user account created for them'),
    );
    
    if(\Drupal::moduleHandler()->moduleExists('shib_groups')){
      $form['other']['force_shib_groups'] = array(
        '#type' => 'checkbox',
        '#title' => 'Only allow users with valid Shib Groups?',
        '#return_value' => 1,
        '#default_value' => $this->config('shib_auth.settings')->get('force_shib_groups'),
        '#description' => t('If checked, only users with a Shib Group that is active within the Shib Groups module will be allowed to log in or create an account'),
      );
    }
    
       
    return parent::buildForm($form, $form_state);
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
  
    $this->config('shib_auth.settings')
      ->set('username_field', $form_state->getValue('username_field'))
      ->set('email_field', $form_state->getValue('email_field'))
      ->set('login_link', $form_state->getValue('login_link'))
      ->set('autocreate_accounts', $form_state->getValue('autocreate_accounts'))
      ->set('force_shib_groups', $form_state->getValue('force_shib_groups'))
      ->save();
  }
  
}
