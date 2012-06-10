{include file='admin_header.tpl'}

{literal}
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


<h2>{lang_print id=100016067} {$user_username}</h2>
{lang_print id=100016068}

<br><br>

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_viewusers_edit.php?user_id={$user_id}'>{lang_print id=100016841}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='admin_userpoints_userstats.php?user_id={$user_id}'>{lang_print id=100016842}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_usertransactions.php?user_id={$user_id}'>{lang_print id=100016843}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_userquotas.php?user_id={$user_id}'>{lang_print id=100016844}</a></td>
<td class='tab3'>&nbsp;<a href='admin_userpoints_viewusers.php'>&#171; &nbsp; {lang_print id=100016845}</a></td>
</tr>
</table>

<br>


<div style='padding: 5px; border: 1px dashed #CCCCCC; text-align: center;' Xwidth='550' Xheight='420'>

  {* SHOW CHART *}
  {if $chart != ""}

    <br>
    <form action='admin_userpoints_userstats.php?user_id={$user_id}' method='get'>
    <table cellpadding='0' cellspacing='0' align='center'>
    <tr>
    <td style='padding-right: 20px;'><a href='admin_userpoints_userstats.php?user_id={$user_id}&period={$period}&graph={$graph}&start={math equation='p+1' p=$start}'><img src='../images/admin_arrowleft.gif' border='0' class='icon2'>{lang_print id=100016083}</a></td>
    <td>{lang_print id=100016072}&nbsp;</td>
    <td>
      <select name='period' class='text'>
      <option value='week'{if $period == "week"} SELECTED{/if}>{lang_print id=100016073}</option>
      <option value='month'{if $period == "month"} SELECTED{/if}>{lang_print id=100016074}</option>
      <option value='year'{if $period == "year"} SELECTED{/if}>{lang_print id=100016075}</option>
      </select>&nbsp;
    </td>
    <td>
      <input type='submit' class='button_small' value='{lang_print id=100016076}'>
    </td>
    <td style='padding-left: 20px;'><a href='admin_userpoints_userstats.php?user_id={$user_id}&period={$period}&graph={$graph}&start={math equation='p-1' p=$start}'>{lang_print id=100016084}<img src='../images/admin_arrowright.gif' border='0' class='icon' style='margin-left: 5px;'></a></td>
    </tr>
    </table>
    <input type='hidden' name='graph' value='{$graph}'>
    <input type='hidden' name='user_id' value='{$user_id}'>
    </form>
    <br>
    {$chart}

  {else}
    
    {lang_print id=100016082}

  {/if}

</div>
</td>
</tr>
</table>

{* AUTO-ACTIVATE FLASH OBJECTS IN IE *}
<script type="text/javascript" src="../include/js/activate_flash.js"></script>

{include file='admin_footer.tpl'}