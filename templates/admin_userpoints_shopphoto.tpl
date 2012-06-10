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

</style>
{/literal}


<h2>{lang_print id=100016480}</h2>
{lang_print id=100016481}

<br><br>
<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_shop.php?task=edit&item_id={$item_id}'>{lang_print id=100016486}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='admin_userpoints_shopphoto.php?item_id={$item_id}'>{lang_print id=100016487}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_shopcomments.php?item_id={$item_id}'>{lang_print id=100016488}</a></td>
<td class='tab3'>&nbsp;<a href='admin_userpoints_shop.php'>&#171; &nbsp; {lang_print id=100016489}</a></td>
</tr>
</table>

<br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

<table cellpadding='0' cellspacing='0'>
<form action='admin_userpoints_shopphoto.php' method='POST' enctype='multipart/form-data'>
<tr>
<td class='form1'>{lang_print id=100016482}</td>
<td class='form2'><img border="0" class="photo" src="{$item_photo}"/></td>
</tr>

<tr>
<td class='form1'>{lang_print id=100016483}</td>
<td class='form2'><input type="file" size="40" class="text" name="photo"/></td>
</tr>


<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>&nbsp;</td>
</tr>

<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td><input type='submit' class='button' value='{lang_print id=100016484}'>&nbsp;</td>
  <input type='hidden' name='task' value='upload'>
  <input type='hidden' name='item_id' value='{$item_id}'>
  </form>
  <form action='admin_userpoints_shop.php' method='POST'>
  <td><input type='submit' class='button' value='{lang_print id=100016485}'></td>
  </tr>
  </form>
  </table>
</td>
</tr>
</table>




{include file='admin_footer.tpl'}