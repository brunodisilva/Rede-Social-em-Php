{include file='admin_header.tpl'}
<h2>Twitter Plugin Setup</h2>
<br>
{if $wrong_php_version}
	This plugin will only run with php<b>5</b>!
{else}
	{if $error}
		<div style="color:#ff0000;font-weight:bold"><b><u>Error!</u></b><br>{$error}</div>
		<br>
	{/if}

	<form name='install_plugin' id="install_plugin" action='admin_viewplugins.php' method='get'>
	<input type="hidden" name="install" value="twitter">
	<input type="hidden" name="go" value="1">
	<input type="hidden" name="setup_mode" value="{$setup_mode}">

	<i>Please fill out <b>all fields</b> to continue.</i><br><br>

	{if !empty($setup_mode)}
		<input type="checkbox" name="upgrade_confirm" value="1" {if !empty($upgrade_confirm)}checked{/if}> Yes, upgrade my twitter plugin version. I created a backup of all my data.

		<br>&nbsp;&nbsp;&nbsp;&nbsp;<i>(Please click this box to continue)</i>
		<br><br>		

	{else}
		<strong>A fresh copy of the twitter plugin will be installed on your server!</strong>

		<br>

		Please take a backup of all your data before you continue.
	{/if}

	<br><br>

	
	<b>Your SEplugins Customer-ID:</b><br>
	<input type="text" name="customer_id" value="123456789" size="15">		
	<br><br>
	
	<b>Your Email Address:</b><br>
	<input type="text" name="email" value="Blow_me@whothefuckcares.com" size="30">	
	
	<br><br>
	<b>License Key:</b><br>
	<input type="text" name="lkey" value="XXXX-XXXX-XXXX-XXXX" size="60">
	<br><br>

	<div id="submit_button" style="display:">

	<input type="button" onClick="document.install_plugin.submit();document.getElementById('submit_button').style.display='none';document.getElementById('submit_wait').style.display='';" class="button" value="{if !empty($setup_mode)}Upgrade Twitter Plugin{else}Install Twitter Plugin{/if}"> <input type="button" class="button" value="Cancel" onClick="location.href='admin_viewplugins.php'">

	</div>
	
	<div id="submit_wait" style="display:none">
		<img src="../images/twitter_install_wait.gif" border="0" alt="Please wait ..." title="Please wait ...">
		<br>&nbsp;&nbsp;&nbsp;Installing ... please wait!
	</div>
	<br><br><br>
	

	Report abuse to report-abuse@seplugins.com	
	<br>
	<a href="http://seplugins.com/abuse" target="_blank">http://seplugins.com/abuse</a>	
	

	</form>
{/if}
{include file='admin_footer.tpl'}

