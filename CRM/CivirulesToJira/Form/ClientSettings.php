<?php

use CRM_CivirulesToJira_ExtensionUtil as E;

/**
 * Form controller class
 * Lots of inspiration drawn from https://github.com/eileenmcnaughton/nz.co.fuzion.civixero/blob/master/CRM/Civixero/Form/XeroSettings.php
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_CivirulesToJira_Form_ClientSettings extends CRM_OauthSync_Form_ConnectionSettings {

  protected function getConnectionSettingsPrefix() {
    return 'jira';
  }

  protected function getHumanReadableConnectionName() {
    return "JIRA";
  }

}
