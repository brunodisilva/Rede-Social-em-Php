{include file='admin_header.tpl'}

{*  $Id: admin_install_fbconnect.tpl 1 2009-07-04 09:36:11Z SocialEngineAddOns $ *}

<h2>Facebook Connect Plugin Setup</h2>
<br>
<strong>A fresh copy of the Facebook Connect Plugin will be installed on your server!</strong><br>
<strong>Please take a backup of your site's code and database before you continue.</strong><br>

	{if !empty($error_message_lsetting)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message_lsetting}</div>
	{/if}
	{if !empty($error_message)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
	{/if}
	{if !empty($error_message_button)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message_button}</div>
	{/if}
	{if !empty($error_message_site_name)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message_site_name}</div>
	{/if}
	{if !empty($error_message_redirect_url)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message_redirect_url}</div>
	{/if}
	{if !empty($error_message_invite_message)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message_invite_message}</div>
	{/if}
	
	<form name='install_plugin' id="install_plugin" action='admin_viewplugins.php?install=fbconnect' method='POST'>
	<input type="hidden" name="install" value="fbconnect">
	<input type="hidden" name="task" value="check">
	
	<b>Please fill out all the fields below.<br><br>
	
        <table cellpadding="0" cellspacing="0" width="600">
        <tbody>
          <tr>
            <td class="header">
            	License Key 
						</td>
          </tr>
          <tr>
            <td class="setting1">Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area..</td>
          </tr>
          <tr>
            <td class="setting2">
							 <input type='text' class='text' name='lsettings' value='{$result.lsettings}' size='50' maxlength='100'>
              Format: XXXXXX-XXXXXX-XXXX 
						</td>
          </tr>
        </tbody>
      </table>
			<br />
			
			
	   <table cellpadding="0" cellspacing="0" width="600">
        <tbody>
          <tr>
            <td class="header">
							Facebook keys 
						</td>
          </tr>
          <tr>
            <td class="setting1">
							<b>Facebook API KEY </b>
						</td>
          </tr>
          <tr>
            <td class="setting2">
							<input type='text' class='text' name='api_key' value='{$result.api_key}' size='50' maxlength='200'><br />
              Enter the API key given to you by Facebook for the Application created by you for this Facebook Connect implementation. Example : a123538b5f31c5730cbc12336b6dc1ca
						</td>
          </tr>
          <tr>
            <td class="setting1">
							<b>Facebook Application Secret key </b>
						</td>
          </tr>
          <tr>
            <td class="setting2">
							 <input type='text' class='text' name='secret' value='{$result.secret}' size='50' maxlength='200'><br />
             	 Enter the Application Secret key given by Facebook. Example : f11fb09e06de94f42504b68cd37d2123
						</td>
          </tr>
        </tbody>
      </table>
			<br />
			
     <table cellpadding="0" cellspacing="0" width="600">
      <tbody>
        <tr>
          <td class="header">
						Facebook Connect Site Settings 
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>Connect button type</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						<table cellpadding="0" cellspacing="0">
							<tr>
									<td width="25"><input name="facebook_button" id="facebook_button_image_1" value="large_long" {if $result.facebook_button == 'large_long'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_1"><img src = '../images/icons/Connect_white_large_long.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_2" value="large_short" {if $result.facebook_button == 'large_short'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_2"><img src = '../images/icons/Connect_white_large_short.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_3" value="medium_long" {if $result.facebook_button == 'medium_long'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_3"><img src = '../images/icons/Connect_white_medium_long.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_4" value="medium_short" {if $result.facebook_button == 'medium_short'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_4"><img src = '../images/icons/Connect_white_medium_short.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_5" value="small_short" {if $result.facebook_button == 'small_short'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_5"><img src = '../images/icons/Connect_white_small_short.gif' border = '0'></label></td>
								</tr>			
							<tr>
								<td colspan="2" style="padding-top:5px;">
									Select the button type that should be shown on your site for Facebook Connect 
								</td>
							</tr>
						</table>
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>Your site name</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						<input type='text' class='text' name='site_name' value='{$result.site_name}' size='50' maxlength='100'><br>Enter your site name here. Example : Social Engine
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>Redirect url.</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						<input type='text' class='text' name='redirect_url' value='{$result.redirect_url}' size='50' maxlength='100'><br>Enter the URL of the page where you want to redirect users after invitation page. Example : http://www.example.com/user_home.php
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>Invite message</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						{if !empty($result.invite_message)}<textarea class="text" name="invite_message" rows="5" cols="50" style="width: 100%;">{$result.invite_message}</textarea>{else}<textarea class="text" name="invite_message" rows="5" cols="50" style="width: 100%;"></textarea>{/if}<br>Enter the Friends Invite message here. Example : Enjoy the new Facebook Connect functionality on SE.
					</td>
        </tr>
      </tbody>
    </table>

	
	<br><br>
	
	<div id="submit_button" style="display:">
	<input type="button" onClick="document.install_plugin.submit();document.getElementById('submit_button').style.display='none';document.getElementById('submit_wait').style.display='';" class="button" value="Install Fbconnect Plugin"> <input type="button" class="button" value="Cancel" onClick="location.href='admin_viewplugins.php'">
	</div>
	
	<div id="submit_wait" style="display:none">
		<br>&nbsp;&nbsp;&nbsp;Installing Facebook Connect... Please wait!
	</div>
	<br><br><br>
	

	
	</form>


{include file='admin_footer.tpl'}