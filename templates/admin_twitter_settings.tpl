{include file='admin_header.tpl'}

<h2>Twitter Global Settings</h2>
Edit global twitter settings and monitor your site rate-limit.

<br><br>

{if !empty($result)}
<div class='success'><img src='../images/success.gif' class='icon' border='0'> Changes have been saved!</div>
{/if}


<table cellpadding='0' cellspacing='0' width='600'>

<tr><td class='header'>Rate-Limit</td></tr>

<tr><td class='setting1'><b>The Twitter API only allows clients to make a limited number of calls in a given hour. [<a href="http://apiwiki.twitter.com/Rate-limiting" target="_blank">more</a>]</b></td></tr>
<tr><td class='setting2'>
This index bar displays how often you reached the Twitter Rate Limit within the last 90 days.<br><br>

{if $rate_limit_index == 5} 
	<div style="padding:2px;width:200px;background:#fff;border:1px solid #c0c0c0"><div style="height:20px;width:200px;background:url(../images/twitter_rate_limit_index.png) no-repeat;background-position: 0px -100px;"></div></div>
	<br>It is impossible to connect to Twitter! Twitter can white-list your site, please check <a href="http://apiwiki.twitter.com/Rate-limiting" target="_blank">this site</a>.
{elseif $rate_limit_index == 4}
	<div style="padding:2px;width:200px;background:#fff;border:1px solid #c0c0c0"><div style="height:20px;width:200px;background:url(../images/twitter_rate_limit_index.png) no-repeat;background-position: 0px -80px;"></div></div>
	<br>Twitter can white-list your site, please check <a href="http://apiwiki.twitter.com/Rate-limiting" target="_blank">this site</a>. You definitely need to increase the caching lifetime!<br>(in /header_twitter.php)
{elseif $rate_limit_index == 3}
	<div style="padding:2px;width:200px;background:#fff;border:1px solid #c0c0c0"><div style="height:20px;width:200px;background:url(../images/twitter_rate_limit_index.png) no-repeat;background-position: 0px -60px;"></div></div>
	<br>You definitely need to increase the caching lifetime!<br>(in /header_twitter.php)
{elseif $rate_limit_index == 2}
	<div style="padding:2px;width:200px;background:#fff;border:1px solid #c0c0c0"><div style="height:20px;width:200px;background:url(../images/twitter_rate_limit_index.png) no-repeat;background-position: 0px -40px;"></div></div>
	<br>Everything is okay. In peak times you get rate-limited. You may want to increase the caching lifetime.<br>(in /header_twitter.php)
{elseif $rate_limit_index == 1}
	<div style="padding:2px;width:200px;background:#fff;border:1px solid #c0c0c0"><div style="height:20px;width:200px;background:url(../images/twitter_rate_limit_index.png) no-repeat;background-position: 0px -20px;"></div></div>
	<br>Everything is okay. In peak times you get rate-limited.
{else}{* index: 0 *}
	<div style="padding:2px;width:200px;background:#fff;border:1px solid #c0c0c0"><div style="height:20px;width:200px;background:url(../images/twitter_rate_limit_index.png) no-repeat;background-position: 0px 0px;"></div></div>
	<br>Everything is working like a charm. Not rate-limited yet.
{/if} 

<br><br>
<a href="admin_twitter_settings.php?task=truncate_rate_limiting" onClick="if(!window.confirm('Are you sure?')) {literal}{{/literal}this.blur();return false;{literal}}{/literal}"><i>Clear Index Status</i></a>

</td></tr>

</table>

<br>


<form action="admin_twitter_settings.php" method="post">


<table cellpadding='0' cellspacing='0' width='600'>

<tr><td class='header'>Privacy Settings</td></tr>

<tr><td class='setting1'><b>Public Timeline and Search</b></td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='checkbox' name='setting[setting_twitter_show_in_main_menu]' value='1' {if $setting.setting_twitter_show_in_main_menu}checked{/if}>&nbsp;</td><td>Show link in main menu and give guests the ability to search and display the public timeline</td></tr>
  </table>
</td></tr>

</table>

<br>


<table cellpadding='0' cellspacing='0' width='600'>

<tr><td class='header'>Tweet/ Update Settings</td></tr>

<tr><td class='setting1'><b>Twitter-Status &gt; Community-Status</b></td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='checkbox' name='setting[setting_twitter_twitter2status]' value='1' {if $setting.setting_twitter_twitter2status}checked{/if}>&nbsp;</td><td>Update Community-Status whenever a tweet (= status update) is sent to Twitter through this site</td></tr>
  </table>
</td></tr>

<tr><td class='setting1'><b>Twitter-Status &lt; Community-Status</b></td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='checkbox' name='setting[setting_twitter_status2twitter]' value='1' {if $setting.setting_twitter_status2twitter}checked{/if}>&nbsp;</td><td>Update Twitter-Status whenever the Community-Status is updated (text is truncated to 140 characters)</td></tr>
  <tr><td>&nbsp;</td><td><br><b>This option requires a small modification in /misc_js.php:</b>
  	<br>
  	<ul>
  		<li>Open /misc_js.php and find these two lines:<br>
<pre>
// CHANGE STATUS
elseif( $task == "status_change" )
</pre>
  		</li>
  		
  		<li>In the following code there is an "if/else" block, add:<br>
<pre>
/* Update Twitter-Status If Community-Status Is Updated */
SEP_Twitter_Status2Twitter($_POST['status']);
</pre>	  

	... somewhere in the "else" block.<br>&nbsp;
  </li>
  <li>Save the file and upload it back to your server.</li>
  	</ul>
  
  </td></tr>
  </table>
</td></tr>

</table>



<br>



<table cellpadding='0' cellspacing='0' width='600'>

<tr><td class='header'>Twitter Authentication</td></tr>

<tr><td class='setting1'><b>Standard Authentication</b> (default)<br><i>Tweet one second ago from <u>web</u></i></td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='radio' name='setting[setting_twitter_authentication]' value='standard' {if $setting.setting_twitter_authentication == 'standard'}checked{/if}>&nbsp;</td><td>Connect to Twitter with Screenname and Password</td></tr>
  </table>
</td></tr>

<tr><td class='setting1'><b>OAuth Authentication</b> (recommended)<br><i>Tweet one second ago from <u>your community name</u></i></td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='radio' name='setting[setting_twitter_authentication]' value='oauth' {if $setting.setting_twitter_authentication == 'oauth'}checked{/if}>&nbsp;</td><td>Connect to Twitter with OAuth</td></tr>
  <tr><td>&nbsp;</td><td>
  	<br>
  	<b>How does OAuth work?</b>
  	<ul>
  		<li>The user clicks a link on your site which redirects to the Twitter Page.</li>
  		<li>Twitter will ask the user for the screenname and password.</li>
  		<li>If the user allows your site to access his profile, Twitter will send an authentication token back.</li>
  	</ul>
	<b>Why should I use OAuth?</b>
	<ul>
		<li>The users password is not saved in your database</li>
		<li>It is easier for the user to connect and more serious</li>
		<li>Define your own tweet source for every tweet<br>
			<b>(source will be displayed within 48 hours after twitter app registration)</b></li>
	</ul>
	
	<b>OAuth Configuration Settings</b><br>
	Where do I get these information from?<br>
	<ul>
		<li>Please find a tutorial on <a href="http://socialenginebase.net/viewtopic.php?t=1299" target="_blank">SocialEngineBase</a></li>
		<li><a href="http://twitter.com/oauth_clients/new" target="_blank">Register your application</a> and get the required OAuth keys<br><i>(callback URL is http://your-site.com/[your-subfolder]/twitter_oauth_confirm.php)</i></li>	
	</ul>

	<table border="0" cellpadding="2" cellspacing="3"> 
		<tr>
			<td align="right">Twitter OAuth Consumer Key:</td>
			<td><input type="text" name="setting[setting_twitter_oauth_consumer_key]" value="{$setting.setting_twitter_oauth_consumer_key}" class="text" size="30"></td>
		</tr>
		<tr>
			<td align="right">Twitter OAuth Consumer Secret-Key:</td>
			<td><input type="text" name="setting[setting_twitter_oauth_consumer_secret]" value="{$setting.setting_twitter_oauth_consumer_secret}" class="text" size="50"></td>
		</tr>	
	</table>
	{if $setting.setting_twitter_oauth_consumer_key}
		&nbsp;&nbsp;&nbsp;<b>Note:</b> If you change your consumer key (or secret-key), all members need to connect to Twitter again.
	{/if}
	
  </td></tr>
  </table>
  
</table>


<br>

<br><br>
<input type='submit' class='button' value='Save Changes'> <input type="button" value="Clear Twitter Cache" class="button" onClick="location.href='admin_twitter_settings.php?task=clear_cache'">
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}