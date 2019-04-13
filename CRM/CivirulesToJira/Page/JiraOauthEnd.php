<?php
use CRM_CivirulesToJira_ExtensionUtil as E;

class CRM_CivirulesToJira_Page_JiraOauthEnd extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('OAuthCallback'));

    //verify the callback
    if(CRM_JiraSync_JiraApiHelper::verifyState($_GET['state'])) {
      CRM_JiraSync_JiraApiHelper::doOAuthCodeExchange($_GET['code']);
      echo "success";
    } else {
      echo "error";
    }


    parent::run();
  }

}
