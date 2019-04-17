<?php
/**
 * Created by IntelliJ IDEA.
 * User: hjed
 * Date: 9/09/18
 * Time: 6:04 PM
 */

return array(
    'jira_cloud_id' => array(
      'group_name' => 'Jira Settings',
      'group' => 'jira_settings',
      'name' => 'jira_cloud_id',
      'type' => 'String',
      'add' => '4.4',
      'is_domain' => 1,
      'is_contact' => 0,
      'description' => 'JIRA "cloudid" for the domain we are connected to',
      'title' => 'JIRA CloudID',
      'help_text' => '',
      'default' => false,
    ),
    'jira_api_token' => array(
      'group_name' => 'JIRA  Settings',
      'group' => 'jira',
      'name' => 'jira_api_token',
      'type' => 'String',
      'add' => '4.4',
      'is_domain' => 1,
      'is_contact' => 0,
      'description' => 'Jira API Token for an admin user from https://id.atlassian.com/manage/api-tokens',
      'title' => 'JIRA API Token',
      'help_text' => 'This is used for operations apps can\'t do like invite users, only set if you need those operations',
      'html_type' => 'Text',
      'html_attributes' => array(
        'size' => 50,
      ),
      'quick_form_type' => 'Element',
    ),
    'jira_api_token_email' => array(
      'group_name' => 'JIRA  Settings',
      'group' => 'jira',
      'name' => 'jira_api_token_email',
      'type' => 'String',
      'add' => '4.4',
      'is_domain' => 1,
      'is_contact' => 0,
      'description' => 'Email address to use with the api token',
      'title' => 'JIRA API Token User Email Address',
      'help_text' => 'This is used for operations apps can\'t do like invite users, only set if you need those operations',
      'html_type' => 'Text',
      'html_attributes' => array(
        'size' => 50,
      ),
      'quick_form_type' => 'Element',
    ),
    'jira_api_token_site' => array(
      'group_name' => 'JIRA  Settings',
      'group' => 'jira',
      'name' => 'jira_api_token_site',
      'type' => 'String',
      'add' => '4.4',
      'is_domain' => 1,
      'is_contact' => 0,
      'description' => 'Site to use the token on e.g. https://example.atlassian.net',
      'title' => 'Site URL for API Token',
      'help_text' => 'This is used for operations apps can\'t do like invite users, only set if you need those operations',
      'html_type' => 'Text',
      'html_attributes' => array(
        'size' => 50,
      ),
      'quick_form_type' => 'Element',
    ),
  ) +
  CRM_OAuthSync_Settings::generateSettings('jira', 'JIRA');

?>