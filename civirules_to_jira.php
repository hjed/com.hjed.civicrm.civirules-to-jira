<?php

require_once 'civirules_to_jira.civix.php';
use CRM_CivirulesToJira_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function civirules_to_jira_civicrm_config(&$config) {
  _civirules_to_jira_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function civirules_to_jira_civicrm_xmlMenu(&$files) {
  _civirules_to_jira_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function civirules_to_jira_civicrm_install() {
  _civirules_to_jira_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function civirules_to_jira_civicrm_postInstall() {
  _civirules_to_jira_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function civirules_to_jira_civicrm_uninstall() {
  _civirules_to_jira_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function civirules_to_jira_civicrm_enable() {
  _civirules_to_jira_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function civirules_to_jira_civicrm_disable() {
  _civirules_to_jira_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function civirules_to_jira_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _civirules_to_jira_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function civirules_to_jira_civicrm_managed(&$entities) {
  _civirules_to_jira_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function civirules_to_jira_civicrm_caseTypes(&$caseTypes) {
  _civirules_to_jira_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function civirules_to_jira_civicrm_angularModules(&$angularModules) {
  _civirules_to_jira_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function civirules_to_jira_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _civirules_to_jira_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function civirules_to_jira_civicrm_entityTypes(&$entityTypes) {
  _civirules_to_jira_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function civirules_to_jira_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function civirules_to_jira_civicrm_navigationMenu(&$menu) {
  _civirules_to_jira_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _civirules_to_jira_civix_navigationMenu($menu);
} // */
/**
 * Implements hook_civicrm_oauthsync_consent_success().
 *
 * Used to get the connection id
 */
function civirules_to_jira_civicrm_oauthsync_consent_success(&$prefix) {

  $ids = CRM_CivirulesToJira_JiraApiHelper::retrieveJiraCloudId();
  if(count($ids) > 1) {
    //TODO: handle multiple ids
    echo "Too many ids";
    die();
  } else if(count($ids) == 1) {
    Civi::settings()->set("jira_cloud_id", $ids[0]);
  } else {
    //TODO: handle this
    echo "request failed";
    die();
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function civirules_to_jira_civicrm_navigationMenu(&$menu) {
  _civirules_to_jira_civix_insert_navigation_menu($menu, 'Administer', array(
    'label' => E::ts('JIRA Settings'),
    'name' => 'JIRA',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _civirules_to_jira_civix_insert_navigation_menu($menu, 'Administer/JIRA', array(
    'label' => E::ts('JIRA API Settings'),
    'name' => 'jira_sync_settings',
    'url' => 'civicrm/civirules-to-jira/form/client-settings',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _civirules_to_jira_civix_insert_navigation_menu($menu, 'Administer/JIRA', array(
    'label' => E::ts('JIRA Connection'),
    'name' => 'jira_sync_oauth_start',
    'url' => 'civicrm/civirules-to-jira/oauth/start',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _civirules_to_jira_civix_navigationMenu($menu);
}

require_once "CRM/CivirulesToJira/JiraApiHelper.php";
require_once CRM_Extension_System::singleton()->getMapper()->keyToPath('com.hjed.civicrm.oauth-sync');
CRM_CivirulesToJira_JiraApiHelper::oauthHelper();