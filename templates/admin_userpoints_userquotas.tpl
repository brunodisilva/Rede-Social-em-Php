{include file='admin_header.tpl'}

{literal}
<script>
function up_edit_inplace(id) {
  document.getElementById("upshow_"+id).style.display='none';
  document.getElementById("upedit_"+id).style.display='block';
  document.getElementById("upinput_"+id).focus();
}
</script>

<style>
table.tabs {
	margin-bottom: 12px;
}
td.tab {
	background: #FFFFFF;
	padding-left: 1px;
	border-bottom: 1px solid #CCCCCC;
}
td.tab0 {
	font-size: 1pt;
	padding-left: 7px;
	border-bottom: 1px solid #CCCCCC;
}
td.tab1 {
	border: 1px solid #CCCCCC;
	border-top: 3px solid #AAAAAA;
	border-bottom: none;
	font-weight: bold;
	padding: 6px 8px 6px 8px;
}
td.tab2 {
	background: #F8F8F8;
	border: 1px solid #CCCCCC;
	border-top: 3px solid #CCCCCC;
	font-weight: bold;
	padding: 6px 8px 6px 8px;
}
td.tab3 {
	background: #FFFFFF;
	border-bottom: 1px solid #CCCCCC;
	padding-right: 12px;
	width: 100%;
	text-align: right;
	vertical-align: middle;
}

.tabs A {
  text-decoration: none;
}

.tabs A:hover {
  text-decoration: underline;
}

td.result {
	font-weight: bold;
	text-align: center;
	border: 1px dashed #CCCCCC;
	background: #FFFFFF;
	padding: 7px 8px 7px 7px;
}
td.error {
	font-weight: bold;
	color: #FF0000;
	text-align: center;
	padding: 7px 8px 7px 7px;
	background: #FFF3F3;
}
td.success {
	font-weight: bold;
	padding: 7px 8px 7px 7px;
	background: #f3fff3;
}
</style>
{/literal}


<h2>{lang_print id=100016522} {$user_username}</h2>
{lang_print id=100016523}

<br><br>

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_viewusers_edit.php?user_id={$user_id}'>{lang_print id=100016835}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_userstats.php?user_id={$user_id}'>{lang_print id=100016836}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_usertransactions.php?user_id={$user_id}'>{lang_print id=100016837}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='admin_userpoints_userquotas.php?user_id={$user_id}'>{lang_print id=100016838}</a></td>
<td class='tab3'>&nbsp;<a href='admin_userpoints_viewusers.php'>&#171; &nbsp; {lang_print id=100016839}</a></td>
</tr>
</table>

<br>

{if $result != 0}

  {if empty($error)}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=100016527}</div>
  {else}
    <div class='error'><img src='../images/error.gif' class='icon' border='0'> {$error} </div>
  {/if}

{/if}

<br>

<form action='admin_userpoints_userquotas.php' method='POST'>

  {* LOOP THROUGH ACTIONS *}
  {section name=action_loop loop=$actions}
  
  <div style="font-weight: bold; width: 600px; text-align: center; padding-bottom: 2px"> {$action_types[action_loop]}</div>
  <table cellpadding='0' cellspacing='0' class='list' Xstyle="width:700px;" width='100%'>
  <tr>
  <td class='header' width="100%">{lang_print id=100016524}</td>
  <td class='header'>{lang_print id=100016525}</td>
  <td class='header'>{lang_print id=100016529}</td>
  <td class='header' nowrap='nowrap'>{lang_print id=100016530}</td>

  <td class='header' nowrap='nowrap'>{lang_print id=100016532}</td>
  <td class='header' nowrap='nowrap'>{lang_print id=100016533}</td>
  <td class='header' nowrap='nowrap'>{lang_print id=100016534}</td>
  </tr>
  
  
  {section name=action_gloop loop=$actions[action_loop]}
    <tr class='{cycle values="background1,background2"}'>
    <td class='item'> {$actions[action_loop][action_gloop].action_name}&nbsp;{if $unavailable}<br><font color="red"> {lang_print id=100016528} {$actions[action_loop][action_gloop].action_requiredplugin}</font> {/if} </td>
    <td class='item'>{$actions[action_loop][action_gloop].action_points}</td>
    <td class='item'>{$actions[action_loop][action_gloop].action_pointsmax}</td>
    <td class='item'>{$actions[action_loop][action_gloop].action_rolloverperiod} {lang_print id=100016531}</td>

    <td class='item'>{$actions[action_loop][action_gloop].userpointcounters_amount}</td>
    <td class='item'>{$actions[action_loop][action_gloop].userpointcounters_cumulative}</td>
    <td class='item'><div style='width:130px'>{if $actions[action_loop][action_gloop].userpointcounters_lastrollover}{$datetime->cdate("`$setting.setting_dateformat` `$setting.setting_timeformat`", $datetime->timezone($actions[action_loop][action_gloop].userpointcounters_lastrollover, $global_timezone))}{else}{lang_print id=100016840}{/if}</div></td>
    </tr>
  {/section}
    
  
  </table>

  <br><br>

  {/section}


<!--<input type='submit' class='button' value='{lang_print id=100016526}'>-->
<!--<input type='hidden' name='task' value='dosave'>-->
</form>

<br><br>
  

{include file='admin_footer.tpl'}