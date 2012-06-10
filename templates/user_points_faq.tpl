{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_vault.php'>{lang_print id=100016741}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_transactions.php'>{lang_print id=100016742}</a></td>
{if $semods_settings.setting_userpoints_enable_offers}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_offers.php'>{lang_print id=100016743}</a></td>
{/if}
{if $semods_settings.setting_userpoints_enable_shop}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_shop.php'>{lang_print id=100016744}</a></td>
{/if}
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_points_faq.php'>{lang_print id=100016745}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>


<img src='./images/icons/help48.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=100016746}</div>
<div>{lang_print id=100016747}</div>

<br><br>

{literal}
<script language="javascript">
<!--
function showhide(id1) {
	if(document.getElementById(id1).style.display=='none') {
		document.getElementById(id1).style.display='block';
	} else {
		document.getElementById(id1).style.display='none';
	}
}
// -->
</script>
{/literal}

<div class='header'>{lang_print id=100016748}</div>
<div class='faq_questions'>
  <a href="javascript:void(0);" onClick="showhide('1');">{lang_print id=100016749}</a><br>
  <div class='faq' style='display: none;' id='1'>{lang_print id=100016750}</div>
</div>
<div class='faq_questions'>
  <a href="javascript:void(0);" onClick="showhide('2');">{lang_print id=100016751}</a><br>
  <div class='faq' style='display: none;' id='2'>
	
	{lang_print id=100016752} <br><br>
	
	<table cellpadding='0' cellspacing='0' class='list' style="width:500px;" Xwidth='100%'>
	<tr>
	<td class='list_header' Xwidth='200' Xstyle='padding-left: 0px;'>{lang_print id=100016753}</td>
	<td class='list_header'>{lang_print id=100016754}</td>
	<td class='list_header'>{lang_print id=100016755}</td>
	<td class='list_header'>{lang_print id=100016756}</td>
	</tr>

	{* LOOP THROUGH ACTIONS *}
	{section name=action_loop loop=$actions}
	
		{section name=action_gloop loop=$actions[action_loop]}
	  
		  <tr class='{cycle values="list_item1,list_item2"}'>
		  <td style="padding:5px" class='item' Xstyle='padding-left: 0px;' Xwidth="100%">{$actions[action_loop][action_gloop].action_name} {if $unavailable}<br><font color="red"> {lang_print id=100016541} {$actions[action_loop][action_gloop].action_requiredplugin}</font> {/if}</td>
		  <td style="padding:5px" class='item' width="20px">{$actions[action_loop][action_gloop].action_points}</td>
		  <td style="padding:5px" class='item' width="20px">{if $actions[action_loop][action_gloop].action_pointsmax == 0}{lang_print id=100016758}{else}{$actions[action_loop][action_gloop].action_pointsmax}{/if}</td>
		  <td style="padding:5px" class='item' width="100px">{if $actions[action_loop][action_gloop].action_rolloverperiod == 0}{lang_print id=100016759}{else} {$actions[action_loop][action_gloop].action_rolloverperiod} {lang_print id=100016757} {/if}</td>
		  </tr>
		
		{/section}
		  
		<tr><td colspan=4 style="border-bottom: 1px dashed #CCC">&nbsp;</td></tr>	
  
	{/section}

	</table>
  
	<br><br>
	
  </div>
</div>

<br>

<div class='header'>{lang_print id=100016760}</div>
<div class='faq_questions'>
  <a href="javascript:void(0);" onClick="showhide('6');">{lang_print id=100016761}</a><br>
  <div class='faq' style='display: none;' id='6'>{lang_print id=100016762}</div>
</div>

<br>

{include file='footer.tpl'}