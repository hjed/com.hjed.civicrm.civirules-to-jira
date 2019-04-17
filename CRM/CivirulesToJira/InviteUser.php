<?php
/**
 * Created by IntelliJ IDEA.
 * User: hjed
 * Date: 13/04/19
 * Time: 9:14 PM
 * Civirules action that creates an issue
 */
class CRM_CivirulesToJira_InviteUser extends CRM_Civirules_Action {

  /**
   * Method to return the url for additional form processing for action
   * and return false if none is needed
   *
   * @param int $ruleActionId
   * @return bool
   * @access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return false;
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

    CRM_CivirulesToJira_JiraApiHelper::getAccountIdOrCreateJiraUser($contactId);
  }


}
