{include file='admin_header.tpl'}

<script src="../include/js/semods.js"></script>

{literal}
<script>
function userpoints_gp_onchange(elem) {
  SEMods.B.hide("div1","div2","div3");
  SEMods.B.show("div"+elem.value);
}
function from_user_id_suggest_onChanged(value) {
  if(value == 0) {
    document.getElementById("user_id_suggest_other").style.display = "inline";
  }
  else {
    SEMods.B.hide("user_id_suggest_other");
  }
}
</script>
{/literal}


<h2>{lang_print id=100016614}</h2>
{lang_print id=100016615}

<br><br>

{if $result != 0}
<div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=100016616}</div>
{/if}

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' class='icon' border='0'> {lang_print id=$error_message}</div>
{/if}

<form action="admin_userpoints_give.php" method="POST">
  
<table cellpadding='0' cellspacing='0' width='600'>
<tr>
<td class='header'>{lang_print id=100016617}</td>
</tr>
<td class='setting1'>

  <table cellpadding='0' cellspacing='0'>
  <tr>
    <td width="50"> {lang_print id=100016618} </td>
    <td>
      <select name="sendtotype" id="sendtotype" onchange="userpoints_gp_onchange(this)">
      <option value="0" {if $sendtotype == 0}SELECTED{/if}>{lang_print id=100016820}</option>
      <option value="1" {if $sendtotype == 1}SELECTED{/if}>{lang_print id=100016821}</option>
      <option value="2" {if $sendtotype == 2}SELECTED{/if}>{lang_print id=100016822}</option>
      <option value="3" {if $sendtotype == 3}SELECTED{/if}>{lang_print id=100016823}</option>
      </select>
    </td>
    <td>
      <div id="div1" {if $sendtotype != 1}style="display:none"{/if}>&nbsp; {lang_print id=100016824} &nbsp;{html_options name="level" options=$levels selected=$level}</div>
      <div id="div2" {if $sendtotype != 2}style="display:none"{/if}>&nbsp; {lang_print id=100016825} <select class='text' name='subnet'>{section name=subnet_loop loop=$subnets}<option value='{$subnets[subnet_loop].subnet_id}'{if $subnet == $subnets[subnet_loop].subnet_id} SELECTED{/if}>{lang_print id=$subnets[subnet_loop].subnet_name}</option>{/section}</select></div>
      <div id="div3" {if $sendtotype != 3}style="display:none"{/if}>&nbsp; {lang_print id=100016826} <input type='text' class='text' name='username' value='{$username}'></div>
    </td>
  </tr>
  <tr>
    <td width="50" style="padding-top: 10px"> {lang_print id=100016827} </td>
    <td style="padding-top: 10px"> <input type='text' class='text' name='points' size=5 value={$points}></td>
  </tr>
  <tr>
    <td width="50"> &nbsp;</td>
    <td style="padding-top: 2px"> <span style="color: #BBB"> {lang_print id=100016626} </span> </td>
  </tr>
  </table>

</td>
</tr>

<tr><td class='setting2' colspan=2>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td><input type='checkbox' name='set_points' id='set_points' value='1'{if $set_points == 1} checked='checked'{/if}></td>
  <td><label for='send_message'>{lang_print id=100016828}</label></td>
  </tr>
  <tr>
  <td><input type='checkbox' name='send_message' id='send_message' value='1'{if $send_message == 1} checked='checked'{/if}></td>
  <td><label for='send_message'>{lang_print id=100016622}</label></td>
  </tr>
  </table>
</td>
</tr>

<tr>
<td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td width='80'>{lang_print id=100016864}</td>
  <td>
    <select name="from_user_id_suggest" onchange="from_user_id_suggest_onChanged(this.value)">
      {foreach from=$from_users_suggest item=user_suggest}
      <option value="{$user_suggest.user_id}" {if $from_user_id_suggest == $user_suggest.user_id}SELECTED{/if}> {$user_suggest.user_displayname} </option>
      {/foreach}
      <option value="0" {if $from_user_id_suggest == 0 AND $from_user_id_suggest != ''}SELECTED{/if}> {lang_print id=100016865} </option>
    </select>
    <span id='user_id_suggest_other' style="padding-left: 5px; display: {if ($from_user_id_suggest == 0 AND $from_user_id != "") OR count($from_users_suggest) == 0}inline{else}none{/if}">
      {lang_print id=100016866} <input type='text' class='text' size='30' name='from_user_id' value='{$from_user_id}'>
    </span>
  </td>
  </tr>
  <tr>
  <td width='80'>{lang_print id=100016619}</td>
  <td><input type='text' class='text' size='30' name='subject' value='{$subject}' maxlength='200'></td>
  </tr>
  <tr>
  <td valign='top'>{lang_print id=100016620}</td>
  <td><textarea rows='6' cols='80' class='text' name='message'>{$message}</textarea></td>
  </tr>
  <tr>
  <td width='80'>&nbsp;</td>
  <td>
    
    <span> {lang_print id=100016867} </span>
    
  </td>
  </tr>
  </table>
</td>
</tr>
</table>

<br>

<input type='submit' class='button' value='{lang_print id=100016621}'>
<input type='hidden' name='task' value='dogivepoints'>
</form>


{include file='admin_footer.tpl'}