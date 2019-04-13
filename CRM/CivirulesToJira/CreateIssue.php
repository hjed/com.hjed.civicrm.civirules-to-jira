<?php
/**
 * Created by IntelliJ IDEA.
 * User: hjed
 * Date: 13/04/19
 * Time: 9:14 PM
 * Civirules action that creates an issue
 */
class CRM_CivirulesToJira_CreateIssue extends CRM_Civirules_Action {

  static $PARAM_PROJECT_KEY = "project_key";
  static $PARAM_USE_CONTACT_NAME_FOR_SUMMARY = "use_contact_name_for_summary";
  static $PARAM_ISSUE_SUMMARY = "issue_summary";
  static $PARAM_ISSUE_TYPE = "issue_type";
  static $PARAM_DESCRIPTION_PROFILE = "description_profile";

  /**
   * Method to return the url for additional form processing for action
   * and return false if none is needed
   *
   * @param int $ruleActionId
   * @return bool
   * @access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return CRM_Utils_System::url('civicrm/civirules-to-jira/form/action/issue/create', 'rule_action_id='.$ruleActionId);
  }

  /**
   * Method processAction to execute the action
   *
   * @param CRM_Civirules_TriggerData_TriggerData $triggerData
   * @access public
   *
   */
  public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
    $contactId = $triggerData->getContactId();


    $subTypes = CRM_Contact_BAO_Contact::getContactSubType($contactId);
    $contactType = CRM_Contact_BAO_Contact::getContactType($contactId);

    $changed = false;
    $action_params = $this->getActionParameters();

    // if we only want to run if it exists
    if($action_params['only_if_not_exist']) {
      $checkParams = array(
        'contact_id' => $contactId,
        'membership_type_id' => $action_params['type']
      );
      $result = civicrm_api3('membership', 'get', $checkParams);
      if($result["count"] > 0) {
        return;
      }
    }

    $createParams = array(
      'contact_id' => $contactId,
      'membership_type_id' => $action_params['type']
    );

    civicrm_api3('membership', 'create', $createParams);
  }


}
