{include file='header.tpl'}

{*  $Id: fbconnect_register_import.tpl 1 2009-07-04 09:36:11Z SocialEngineAddOns $ * }

<div class="width-full fleft">
	<img src="./images/icons/facebook-import.gif" alt="Import" align="left" class="facebook-import" />
	<div class="fleft margin-left-10">
		<div class="page_header fleft">
			{lang_print id=650000058}<br />
		</div>
		<div class="fleft clr">
			{lang_print id=650000053}
		</div>
	</div>
	<form method="POST" action="fbconnect_register_import.php">
		<div class="fleft width-full margin-top-20">
			<div class="signup_header" style="width:550px;">
				{lang_print id=650000054} {$fbname}
			</div>
			<div class="fleft margin-left-10">
				<div class='portal_login' style="padding:10px;">
					{$avatar}
				</div>
			</div>
			<div class="fleft margin-left-10 checkbox-row">
				<input type="checkbox" name="visibility" value="1" align="bottom" checked="checked" />&nbsp; {lang_print id=650000055} {$var_site_name}.<br />
				<span class="message-text"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {lang_print id=650000056}</span><br />
				<div class="fleft margin-top-10 checkbox-row">
					<input type="checkbox" name="fb_avatar" value="1" align="bottom" checked="checked" />&nbsp;{lang_print id=650000057} {$var_site_name}.
				</div><br />
			
			{foreach from=$checkboxes_options key=profile_key item=profile_value}
			
				<div class="fleft margin-top-10 checkbox-row">
					<input type="checkbox" name="{$profile_key}" value="{$profile_key}" align="bottom"{if $profile_key|in_array:$var_fbconnect_field_to_import} checked="checked"{/if} />
					{$profile_value}
				</div>
				
			{/foreach}	
				
				<div class="fleft clr margin-top-10">
					<input type="hidden" name="isassociation" value="{$isassociation}" />
					<input class="button" value="Submit" type="submit" name="op" border="0" />
				</div>
			</div>
		</div>
	</form>
</div>

{include file='footer.tpl'}
