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
  static $PARAM_ASSIGNEE = "assigne";
  static $PARAM_ASSIGNE_DO_NOTHING = "null";
  static $PARAM_ASSIGNE_ASSIGN_IF_EXISTS = "assign_if_exists";
  static $PARAM_ASSIGNE_CREATE_AND_ASSIGN = "create_and_assign";

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
            $this->generateHeader()
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
      $fieldTranslation = $this->getProfileLabelMap($action_params[self::$PARAM_DESCRIPTION_PROFILE]);
      $fieldTypes = $this->getSpecialFieldTypeMap($action_params[self::$PARAM_DESCRIPTION_PROFILE]);
      foreach ($profile['values'] as $key => $value) {
        $valueRender = null;
        if($fieldTypes[$key]) {
          if($fieldTypes[$key]['type'] == 'multi' && sizeof($fieldTypes[$key]['options']) > 0) {
            // handle multi selects
            $listADF = array();
            foreach ($value as $_ => $listItem) {
              $listADF[] = array(
                'type' => 'listItem',
                'content' => array(
                  array(
                    'type' => 'paragraph',
                    'content' => array(
                      array(
                        'type' => 'text',
                        'text' => strval($fieldTypes[$key]['options'][$listItem])
                      )
                    )
                  )
                )
              );
            }
            $valueRender = array(
              'type' => 'bulletList',
              'content' => $listADF
            );
          } elseif($fieldTypes[$key]['type'] == 'select' && sizeof($fieldTypes[$key]['options']) > 0) {
            $value = strval($fieldTypes[$key]['options'][$value]);
          } elseif ($fieldTypes[$key]['type'] == 'contact') {
            $valueRender = $this->renderContact($value);
          }
        }


        if($valueRender == null ) {
          if(!is_string($value)) {
            $value = "Unsupported Field";
          }

          $valueRender = array(
            'type' => 'paragraph',
            'content' => array(
              array(
                'type' => 'text',
                'text' => ($value !=null ? strval($value) : "-")
              )
            )
          );
        }
        $description['content'][1]['content'][] = array(
          'type' => 'tableRow',
          'content' => array(
            array(
              "type" => "tableCell",
              "content" => array(
                array(
                  'attrs' => array(
                    'level' => 3
                  ),
                  'type' => 'heading',
                  'content' => array(
                    array(
                      'type' => 'text',
                      'text' => $fieldTranslation[preg_split('/-/', $key)[0]]
                    )
                  )
                )
              ),
            ),
            array(
              "type" => "tableCell",
              "content" => array(
                $valueRender
              )
            )
          )
        );
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

    $assigneeMode = $action_params[self::$PARAM_ASSIGNEE];
    if($assigneeMode == self::$PARAM_ASSIGNE_ASSIGN_IF_EXISTS) {
      $accountId = CRM_CivirulesToJira_JiraApiHelper::getAtlassianAccountIdIfPresent($contactId);
      if($accountId != null) {
        $issueCreateRequest['fields']['assignee'] = array(
          'id' => $accountId
        );
      }
    } else if($assigneeMode == self::$PARAM_ASSIGNE_CREATE_AND_ASSIGN) {
      $accountId = CRM_CivirulesToJira_JiraApiHelper::getAccountIdOrCreateJiraUser($contactId);
      if($accountId != null) {
        $issueCreateRequest['fields']['assignee'] = array(
          'id' => $accountId
        );
      }
    }


    $respJson = CRM_CivirulesToJira_JiraApiHelper::callJiraApi('/rest/api/3/issue', 'POST', $issueCreateRequest);
  }

  function getProfileLabelMap($profileId) {
    $profileLabels = civicrm_api3('UFField', 'get', [
      'uf_group_id.id' => $profileId
    ]);

    $translation = array();
    foreach ($profileLabels['values'] as $field) {
      $translation[$field['field_name']] = $field['label'];
    }
    return $translation;
  }

  function getSpecialFieldTypeMap($profileId) {
    $profileFields = civicrm_api3('UFField', 'get', [
      'uf_group_id.id' => $profileId
    ]);

    $translation = array();
    foreach ($profileFields['values'] as $field) {
      if(preg_match('/custom_/', $field['field_name'])) {
        $customField = array_pop(civicrm_api3('CustomField', 'get', [
          'id' => preg_filter('/custom_/', '', $field['field_name']),
          'api.OptionValue.get' => array('option_group_id' => '$value.option_group_id', 'return' => array('label', 'value'))
        ])['values']);

        if($customField['data_type'] == 'ContactReference') {
          $translation[$field['field_name']] = array(
            'type' => 'contact'
          );
        } elseif ($customField['html_type'] == 'Select') {
          $translation[$field['field_name']] = array(
            'type' => 'select',
            'options' => $this->translateOptionGroup($customField['api.OptionValue.get']['values'])
          );
        } elseif ($customField['html_type'] == 'Multi-Select') {
          $translation[$field['field_name']] = array(
            'type' => 'multi',
            'options' => $this->translateOptionGroup($customField['api.OptionValue.get']['values'])
          );
        }
      }
    }
    return $translation;
  }

  function translateOptionGroup($optionValues) {
    $translation = array();
    foreach ($optionValues as $option) {
      $translation[$option['value']] = $option['label'];
    }
    return $translation;
  }

  function generateHeader() {
    return array(
      'type' => 'tableRow',
      'content' => array(
        array(
          "type" => "tableCell",
          "content" => array(
            array(
              'attrs' => array(
                'level' => 2
              ),
              'type' => 'heading',
              'content' => array(
                array(
                  'type' => 'text',
                  'text' => 'Field'
                )
              )
            )
          ),
        ),
        array(
          "type" => "tableCell",
          "content" => array(
            array(
              'attrs' => array(
                'level' => 2
              ),
              'type' => 'heading',
              'content' => array(
                array(
                  'type' => 'text',
                  'text' => 'Value'
                )
              )
            )
          )
        )
      )
    );
  }

  function renderContact($contactId) {
    $contact = civicrm_api3('Contact', 'get', [
      'sequential' => 1,
      'id' => $contactId,
    ]);
    $aid = CRM_CivirulesToJira_JiraApiHelper::getAtlassianAccountIdIfPresent($contactId);
    $formatedName = null;
    if($aid) {
      $formatedName = array(
        'type' => 'mention',
        'attrs' => array(
          'id' => $aid
        )
      );
    } else {
      $formatedName = array(
        'type' => 'text',
        'text' => $contact['values'][0]['display_name']
      );
    }
    $valueRender = array(
      'type' => 'paragraph',
      'content' => array(
        $formatedName,
        array(
          'type' => 'text',
          'text' => ' (CiviCRM Contact)',
          'marks' => array(
            array(
              'type' => 'link',
              'attrs' => array(
                'href' => CRM_Utils_System::url("civicrm/contact/view", 'reset=1&cid=' . $contactId, TRUE, NULL, FALSE, TRUE)
              )
            )
          )
        )
      )
    );
    return $valueRender;
  }

}
