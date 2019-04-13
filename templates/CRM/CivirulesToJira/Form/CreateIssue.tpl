
{* header *}
<h3>{$ruleactionheader}</h3>
<div class="crm-block crm-form-block crm-civirule-rule_action-block-contact_subtype">
  <div class="crm-section">
    <div class="label">{$form.project_key.label}</div>
    <div class="content">{$form.project_key.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.use_contact_name_for_summary.label}</div>
    <div class="content">{$form.use_contact_name_for_summary.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.issue_summary.label}</div>
    <div class="content">{$form.issue_summary.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.issue_type.label}</div>
    <div class="content">{$form.issue_type.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.description_profile.label}</div>
    <div class="content">{$form.description_profile.html}</div>
    <div class="clear"></div>
  </div>
</div>

<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
