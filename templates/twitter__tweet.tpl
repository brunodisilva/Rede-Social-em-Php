

<div id="{$instance_name}_Link" class="SEP_Twitter_Tweet_Link" style="display:{if isset($show)}none{else}{/if}"><a href="javascript:void(0)" onClick="{$instance_name}_instance.show_form()"><img src="./images/icons/twitter.gif" border="0" class="icon" style="margin-right:0px;margin-top:-1px;"></a> <a href="javascript:void(0)" onClick="{$instance_name}_instance.show_form()">{lang_print id=18910053}</a> <a href="javascript:void(0)" onClick="{$instance_name}_instance.show_form()"><img src="./images/icons/twitter_down.gif" border="0" style="margin-bottom:-1px;"></a></div>

<form name="{$instance_name}" id="{$instance_name}" onSubmit="{$instance_name}_instance.send(); return false;" style="display:{if isset($show)}{else}none{/if}">
	<input type="hidden" name="in_reply_to_status_id" value="">
	
	<div class="SEP_Twitter_Tweet_Sending" style="display:none;{if $auto_width}width:100%;padding:5px;background:none;border-bottom:0px;{/if}"><br><br>
		<img src="./images/icons/twitter_spinner.gif" border="0"> {lang_print id=18910054}
	</div>
	<div class="SEP_Twitter_Tweet_Success" onClick="{$instance_name}_instance.show_form()" style="display:none; {if $auto_width}width:100%;padding:5px;background:none;border-bottom:0px;{/if}"><br><br><br>
		<img src="./images/icons/twitter_okay.gif" border="0"> {lang_print id=18910055}
	</div>

	<div class="SEP_Twitter_Tweet_Form" style="display:; {if $auto_width}width:100%;padding:0px;background:none;border-bottom:0px;{/if}">
		<div class="SEP_Twitter_Tweet_Characters_Counter">140</div>
		<div><img src="./images/icons/twitter.gif" border="0" class="icon" style="margin-right:0px"> <span class="SEP_Twitter_Tweet_Heading">{lang_print id=18910056}</span></div>
		
		<textarea name="text" class="text SEP_Twitter_Tweet_Textarea" onFocus="{$instance_name}_instance.start()" onBlur="{$instance_name}_instance.stop()"></textarea>
		<div style="width:100%;text-align:right">
			<span class="SEP_Twitter_Tweet_ScreenName">{lang_print id=18910057} {$SEP_TwitterUser->screen_name}</span>
			<input type="submit" value="{lang_print id=18910058}" class="button SEP_Twitter_Tweet_Submit" style="padding:3px 20px;">{if !$hide_cancel}&nbsp;&nbsp;<input type="button" value="{lang_print id=18910059}" class="button SEP_Twitter_Tweet_Submit" onClick="{$instance_name}_instance.show_link()">{/if}
		</div>
	</div>
</form>

<script type="text/javascript">

	var {$instance_name}_instance = new SEP_Twitter_Tweet('{$instance_name}');

</script>