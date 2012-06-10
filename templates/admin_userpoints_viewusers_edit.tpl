{include file='admin_header.tpl'}

{literal}
<script src="../include/js/semods.js"></script>

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
.points_totalearned_edit {
  text-decoration: none;
}

.points_totalearned_edit:hover {
  text-decoration: underline;
}
</style>

<script>
function edit_total() {
  SEMods.B.ge("totalpoints_readonly").style.display = "none";
  SEMods.B.ge("totalpoints_edit").style.display = "";
//  SEMods.B.toggle("totalpoints_readonly", "totalpoints_edit");
}
</script>
{/literal}


<h2>{lang_print id=100016595} {$user_username}</h2>
{lang_print id=100016596}

<br><br>

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='admin_userpoints_viewusers_edit.php?user_id={$user_id}'>{lang_print id=100016852}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_userstats.php?user_id={$user_id}'>{lang_print id=100016853}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_usertransactions.php?user_id={$user_id}'>{lang_print id=100016854}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='admin_userpoints_userquotas.php?user_id={$user_id}'>{lang_print id=100016855}</a></td>
<td class='tab3'>&nbsp;<a href='admin_userpoints_viewusers.php'>&#171; &nbsp; {lang_print id=100016856}</a></td>
</tr>
</table>

<br>

{if $error_message != ""}
  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ""}
  <div class='success'><img src='../images/success.gif' border='0' class='icon'> {$result}</div>
{/if}

<form action='admin_userpoints_viewusers_edit.php' method='POST'>

<table cellpadding='0' cellspacing='0' class='stats'>
<tr>
<td class='stat0'>
<table cellpadding='0' cellspacing='0'>
<tr>
<td><b>{lang_print id=100016597}</b>
<span id="totalpoints_readonly" style="display: inline">{$points_totalearned} <span style="color: #CCC">(</span><a class='points_totalearned_edit' href="javascript:edit_total()"><span style="color: #CCC">{lang_print id=100016613}</span></a><span style="color: #CCC">)</span></span>
<span id="totalpoints_edit" style="display: none"><input type='text' class='text' name='points_totalearned' value='{$points_totalearned}'></span>
</td>
<td style='padding-left: 20px;'><b>{lang_print id=100016598}</b> {$points_totalspent}</td>
</tr>
</table>
</td>
</tr>
</table>

<br>

<table cellpadding='0' cellspacing='0'>
<tr>
<td class='form1'>{lang_print id=100016599}</td>
<td class='form2'><input type='text' class='text' name='user_points' value='{$points}'></td>
</tr>

<tr>
<td class='form1'>{lang_print id=100016605}</td>
<td class='form2'>

<select class='text' name='user_points_enabled'>
<option value='1'{if $user_points_enabled == 1} SELECTED{/if}>{lang_print id=100016611}</option>
<option value='0'{if $user_points_enabled == 0} SELECTED{/if}>{lang_print id=100016612}</option>
</select>

</td>
</tr>

<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td><input type='submit' class='button' value='{lang_print id=100016600}'>&nbsp;</td>
  <input type='hidden' name='task' value='edituser'>
  <input type='hidden' name='user_id' value='{$user_id}'>
  <input type='hidden' name='s' value='{$s}'>
  <input type='hidden' name='p' value='{$p}'>
  <input type='hidden' name='f_user' value='{$f_user}'>
  <input type='hidden' name='f_email' value='{$f_email}'>
  <input type='hidden' name='f_level' value='{$f_level}'>
  <input type='hidden' name='f_enabled' value='{$f_enabled}'>
  </form>
  <form action='admin_userpoints_viewusers_edit.php' method='POST'>
  <td><input type='submit' class='button' value='{lang_print id=100016601}'></td>
  <input type='hidden' name='s' value='{$s}'>
  <input type='hidden' name='p' value='{$p}'>
  <input type='hidden' name='f_user' value='{$f_user}'>
  <input type='hidden' name='f_email' value='{$f_email}'>
  <input type='hidden' name='f_level' value='{$f_level}'>
  <input type='hidden' name='f_enabled' value='{$f_enabled}'>
  </tr>
  </form>
  </table>
</td>
</tr>
</form>
</table>


<br><br>

{include file='admin_footer.tpl'}