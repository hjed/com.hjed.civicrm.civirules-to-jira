<h3>Connected JIRA Instance</h3>


{if $connected}
    <p>You are connected with cloud id {$cloudId}!</p>
    <p><a href="{$oauth_url}">Connect</a></p>
    <!-- TODO: disconnect -->
{else}
    <p>Please authorise a JIRA instance</p>
    <p><a href="{$oauth_url}">Connect</a></p>
{/if}
