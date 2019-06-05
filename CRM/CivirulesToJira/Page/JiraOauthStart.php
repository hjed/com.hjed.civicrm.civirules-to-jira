<?php
use CRM_CivirulesToJira_ExtensionUtil as E;

class CRM_CivirulesToJira_Page_JiraOauthStart extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Your JIRA Connection'));


    $connected = civicrm_api3('Setting', 'get', array('group' => 'jira_token'))["values"][1]['jira_connected'];
    $cloudId = civicrm_api3('Setting', 'get', array('group' => 'jira_settings'))["values"][1]['jira_cloud_id'];
    $client_id = civicrm_api3('Setting', 'get', array('group' => 'jira_settings'))["values"][1]['jira_client_id'];
    print_r(civicrm_api3('Setting', 'get', array('group' => 'jira'))['values'][1]['jira_key']);
    $this->assign('connected', $connected);
    $this->assign('cloudId', $cloudId);
    $state = CRM_CivirulesToJira_JiraApiHelper::oauthHelper()->newStateKey();
    $redirect_url= CRM_OauthSync_OAuthHelper::generateRedirectUrlEncoded();
    CRM_CivirulesToJira_JiraApiHelper::oauthHelper()->setOauthCallbackReturnPath(
      join('/', $this->urlPath)
    );
    $this->assign(
      'oauth_url',
      'https://auth.atlassian.com/authorize?audience=api.atlassian.com&client_id=' . $client_id . '&scope=read:jira-work%20write:jira-work%20offline_access&redirect_uri=' . $redirect_url . '&state=' . $state . '&response_type=code&prompt=consent'
    );
    parent::run();
  }

}
