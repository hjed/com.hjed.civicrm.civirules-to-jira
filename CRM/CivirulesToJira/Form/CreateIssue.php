<?php

use CRM_CivirulesToJira_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_CivirulesToJira_Form_CreateIssue extends CRM_CivirulesActions_Form_Form {

  private function getProjects() {
    $respJson = CRM_CivirulesToJira_JiraApiHelper::callJiraApi('/rest/api/3/project/search', 'GET');
    $projects = $respJson['values'];
    while(!$respJson['isLast']) {
      $respJson = CRM_CivirulesToJira_JiraApiHelper::callJiraApi($respJson['nextPage'], 'GET');
      $projects += $respJson['values'];
    }

    $idToName = array();
    foreach($projects as $project) {
      $idToName[$project['id']] = $project['name'] . '(' . $project['key'] . ')';
    }
    return $idToName;
  }

  private function getIssueTypes() {
    $respJson = CRM_CivirulesToJira_JiraApiHelper::callJiraApi('/rest/api/3/issuetype', 'GET');
    $issueTypes = $respJson['values'];
    foreach($issueTypes as $issueType) {
      $idToName[$issueType['id']] = $issueType['name'];
    }
    return $issueTypes;
  }

  private function getProfiles() {
    $result = civicrm_api3('UFGroup', 'get');

    $options = array();
    foreach ($result['values'] as $profile) {
      $options[$profile['id']] = $profile['title'];
    }
    return $options;
  }

  public function buildQuickForm() {

    $this->add('hidden', 'rule_action_id');

    $this->add(
      'select',
      CRM_CivirulesToJira_CreateIssue::$PARAM_PROJECT_KEY,
      ts('Please select the project to create the issue in'),
      array('' => ts('-- please select --')) + $this->getProjects()
    );

    $this->add(
      'select',
      CRM_CivirulesToJira_CreateIssue::$PARAM_ISSUE_TYPE,
      ts('The status to change the membership too'),
      array('' => ts('-- please select --')) + $this->getIssueTypes()
    );

    $this->add(
      'checkbox',
      CRM_CivirulesToJira_CreateIssue::$PARAM_USE_CONTACT_NAME_FOR_SUMMARY,
      ts('Use Contact Name for Issue Summary')
    );

    $this->add(
      'text',
      CRM_CivirulesToJira_CreateIssue::$PARAM_ISSUE_SUMMARY,
      ts('Issue summary if not using contact name')
    );

    $this->add(
      'select',
      CRM_CivirulesToJira_CreateIssue::$PARAM_DESCRIPTION_PROFILE,
      ts('Profile to use as the issue summary'),
      array('' => ts('-- please select --')) + $this->getProfiles()
    );

    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  /**
   * Overridden parent method to process form data after submitting
   *
   * @access public
   */
  public function postProcess() {
    $data[CRM_CivirulesToJira_CreateIssue::$PARAM_DESCRIPTION_PROFILE] = $this->_submitValues[CRM_CivirulesToJira_CreateIssue::$PARAM_DESCRIPTION_PROFILE];
    $data[CRM_CivirulesToJira_CreateIssue::$PARAM_ISSUE_SUMMARY] = $this->_submitValues[CRM_CivirulesToJira_CreateIssue::$PARAM_ISSUE_SUMMARY];
    $data[CRM_CivirulesToJira_CreateIssue::$PARAM_USE_CONTACT_NAME_FOR_SUMMARY] = $this->_submitValues[CRM_CivirulesToJira_CreateIssue::$PARAM_USE_CONTACT_NAME_FOR_SUMMARY];
    $data[CRM_CivirulesToJira_CreateIssue::$PARAM_ISSUE_TYPE] = $this->_submitValues[CRM_CivirulesToJira_CreateIssue::$PARAM_ISSUE_TYPE];
    $data[CRM_CivirulesToJira_CreateIssue::$PARAM_PROJECT_TYPE] = $this->_submitValues[CRM_CivirulesToJira_CreateIssue::$PARAM_PROJECT_TYPE];

    $this->ruleAction->action_params = serialize($data);
    $this->ruleAction->save();
    parent::postProcess();
  }

}
