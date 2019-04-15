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

    $action_params = $this->getActionParameters();

    $issueSummary = "";
    if($action_params[self::$PARAM_USE_CONTACT_NAME_FOR_SUMMARY]) {
      $contact = civicrm_api3("Contact", "get", array("id" => $contactId));
      $issueSummary = array_pop($contact['values'])['display_name'];
    } else {
      $issueSummary = $action_params[self::$PARAM_ISSUE_SUMMARY];
    }

    $description = array(
      'type' => 'doc',
      'version' => 1,
      'content' => array(
        array(
          'type' => 'heading',
          'attrs' => array(
            'level' => 1
          ),
          'content' => array(
            array(
              'type' => 'text',
              'text' => 'CiviCRM Profile'
            )
          )
        ),
        array(
          'type' => 'table',
          'content'  => array(
          )
        ),
        array(
          'type' => 'paragraph',
          'content'  => array(
            array(
              'type' => 'text',
              'text' => 'Profile as at ' . date(DATE_ISO8601) .' You can view the current profile '
            ),
            array(
              'type' => 'text',
              'text' => 'at CiviCRM',
              "marks" => array(
                array(
                  "type" => "link",
                  "attrs" => array(
                    "href" => CRM_Utils_System::url("civicrm/profile/view", 'reset=1&id=' . $contactId . '&gid='.$action_params[self::$PARAM_DESCRIPTION_PROFILE], TRUE, NULL, FALSE, TRUE)
                  )
                )
              )
            )
          )

        )
      )
    );
    if($action_params[self::$PARAM_DESCRIPTION_PROFILE] != null) {
      $profile = civicrm_api3('Profile', 'get', [
        'sequential' => 1,
        'profile_id' => $action_params[self::$PARAM_DESCRIPTION_PROFILE],
        'contact_id' => $contactId,
      ]);
      foreach ($profile['values'] as $key => $value) {
        if(is_string($value)) {
          $description['content'][1]['content'][] = array(
            'type' => 'tableRow',
            'content' => array(
              array(
                "type" => "tableCell",
                "content" => array(
                  array(
                    'type' => 'paragraph',
                    'content' => array(
                      array(
                        'type' => 'text',
                        'text' => $key
                      )
                    )
                  )
                ),
              ),
              array(
                "type" => "tableCell",
                "content" => array(
                  array(
                    'type' => 'paragraph',
                    'content' => array(
                      array(
                        'type' => 'text',
                        'text' => strval($value)
                      )
                    )
                  )
                )
              )
            )
          );
        } else {
          print_r($key);
          print_r($value);
        }
      }
    }

    $issueCreateRequest = array(
      'fields' => array(
        'summary' => $issueSummary,
        'issuetype' => array('id' => $action_params[self::$PARAM_ISSUE_TYPE]),
        'project' => array('id' => $action_params[self::$PARAM_PROJECT_KEY]),
        'description' => $description
      )
    );


    $respJson = CRM_CivirulesToJira_JiraApiHelper::callJiraApi('/rest/api/3/issue', 'POST', $issueCreateRequest);
  }


}
