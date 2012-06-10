{include file='admin_header.tpl'}

<script type="text/javascript" src="../include/js/semods.js"></script>

{literal}
<script>
function set_ratelimit_value(seconds) {
  document.getElementById("setting_emailer_email_ratelimit_delay").value = seconds;
}
function test_email() {
  SEMods.B.hide("test_email");
  SEMods.B.show("test_email_progress");
  new SEMods.Ajax( on_email_test_success, on_email_test_fail ).post( "admin_emailer.php?task=test", '' );  
}

function on_email_test_success(a, resp) {
  SEMods.B.show("test_email");
  SEMods.B.hide("test_email_progress");
  alert( resp );
}

function on_email_test_fail(a) {
  SEMods.B.show("test_email");
  SEMods.B.hide("test_email_progress");
  alert("Error");
}
</script>
{/literal}

<h2>{lang_print id=100011005}</h2>
{lang_print id=100011006}

<br><br>

{if $result != 0}

  {if empty($error)}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=100011007}</div>
  {else}
    <div class='error'><img src='../images/error.gif' class='icon' border='0'> {$error} </div>
  {/if}

{/if}


<form action='admin_emailer.php' method='POST'>

<table cellpadding='0' cellspacing='0' width='600'>
<tr>
<td class='header'>{lang_print id=100011009}</td>
</tr>

<tr>
<td class='setting1'>
{lang_print id=100011010} {$queued_emails} <br>
{lang_print id=100011020} {$est_time_to_finish_day} {lang_print id=100011021}, {$est_time_to_finish_hour} {lang_print id=100011022}, {$est_time_to_finish_min} {lang_print id=100011023}
</td></tr>

<tr>
<td class='setting1'>
{lang_print id=100011011}
</td></tr><tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='checkbox' value='1' name='setting_emailer_enabled' id='setting_emailer_enabled' {if $setting_emailer_enabled == 1} CHECKED{/if}>&nbsp;</td><td><label>{lang_print id=100011012}</label></td></tr>
  </table>
</td></tr>

<tr><td class='setting1'> {lang_print id=100011013} </td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='text' name='setting_emailer_max_email_retries' size='2' maxlength='3' class='text' value='{$setting_emailer_max_email_retries}'>&nbsp;</td><td>{lang_print id=100011014}</td></tr>
  </table>
</td>
</tr>


<tr><td class='setting1'> {lang_print id=100011015} </td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='text' name='setting_emailer_max_emails_per_batch' size='2' maxlength='3' class='text' value='{$setting_emailer_max_emails_per_batch}'>&nbsp;</td><td>{lang_print id=100011016}</td></tr>
  </table>
</td>
</tr>


<tr><td class='setting1'> {lang_print id=100011027} </td></tr>
<tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
    <td><input type='text' name='setting_emailer_email_ratelimit_delay' id='setting_emailer_email_ratelimit_delay' size='5' Xmaxlength='3' class='text' value='{$setting_emailer_email_ratelimit_delay}'>&nbsp;</td>
    <td>{lang_print id=100011028}</td>
    <td><div style="width:50px">&nbsp;</div></td>
    <td>{lang_print id=100011029} &nbsp;
      <a href="javascript:set_ratelimit_value(12*60*60)">12 {lang_print id=100011030}</a> &nbsp;
      <a href="javascript:set_ratelimit_value(24*60*60)">1 {lang_print id=100011031}</a> &nbsp;
      <a href="javascript:set_ratelimit_value(2*24*60*60)">2 {lang_print id=100011031}</a> &nbsp;
      <a href="javascript:set_ratelimit_value(7*24*60*60)">7 {lang_print id=100011031}</a> &nbsp;
    </td>
  </tr>
  </table>
</td>
</tr>

<tr><td class='setting1'> {lang_print id=100011017}  </td></tr>
<tr><td class='setting2'>
  <select class='text' name='setting_emailer_period'>
  <option value='5'{if $setting_emailer_period == "5"} selected='selected'{/if}>5 {lang_print id=100011018}</option>
  <option value='10'{if $setting_emailer_period == "10"} selected='selected'{/if}>10 {lang_print id=100011018}</option>
  <option value='15'{if $setting_emailer_period == "15"} selected='selected'{/if}>15 {lang_print id=100011018}</option>
  <option value='30'{if $setting_emailer_period == "30"} selected='selected'{/if}>30 {lang_print id=100011018}</option>
  <option value='60'{if $setting_emailer_period == "60"} selected='selected'{/if}>1 {lang_print id=100011019}</option>
  </select>
</td>
</tr>

<tr>
<td class='setting1'>
{lang_print id=100011024}
</td></tr><tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='checkbox' value='1' name='setting_emailer_notify_on_error' id='setting_emailer_notify_on_error' {if $setting_emailer_notify_on_error == 1} CHECKED{/if}>&nbsp;</td><td><label>{lang_print id=100011025}</label></td></tr>
  <tr><td><input type='checkbox' value='1' name='setting_emailer_notify_on_success' id='setting_emailer_notify_on_success' {if $setting_emailer_notify_on_success== 1} CHECKED{/if}>&nbsp;</td><td><label>{lang_print id=100011026}</label></td></tr>
  </table>
</td></tr>

</table>


<br>


<table cellpadding='0' cellspacing='0' width='600'>
<tr>
<td class='header'>{lang_print id=100011032}</td>
</tr>

<tr>
<td class='setting1'>
{lang_print id=100011039}
</td></tr><tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='checkbox' value='1' name='setting_emailer_use_smtp' id='setting_emailer_use_smtp' {if $setting_emailer_use_smtp == 1} CHECKED{/if}>&nbsp;</td><td><label>{lang_print id=100011040}</label></td></tr>
  </table>
</td></tr>


<tr>
<td class='setting1'>
{lang_print id=100011033}
</td></tr><tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td width=80>{lang_print id=100011034}</td><td class='form2'><input type='text' name='setting_emailer_smtp_host' size='50' maxlength='255' class='text' value='{$setting_emailer_smtp_host}'>&nbsp;</td></tr>
  </table>
</td></tr>

<tr>
<td class='setting1'>
{lang_print id=100011038}
</td></tr><tr><td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr><td width=80>{lang_print id=100011035}</td><td class='form2'><input type='text' name='setting_emailer_smtp_port' size='5' maxlength='5' class='text' value='{$setting_emailer_smtp_port}'>&nbsp;</td></tr>
  <tr><td width=80>{lang_print id=100011036}</td><td class='form2'><input type='text' name='setting_emailer_smtp_user' size='50' maxlength='255' class='text' value='{$setting_emailer_smtp_user}'>&nbsp;</td></tr>
  <tr><td width=80>{lang_print id=100011037}</td><td class='form2'><input type='password' name='setting_emailer_smtp_pass' size='50' maxlength='255' class='text' value='{$setting_emailer_smtp_pass}'>&nbsp;</td></tr>
  </table>
</td></tr>

<tr>
<td class='setting1'>
{lang_print id=100011041}
<br>
<div id="test_email" style="text-align: center; display:block">
<input type='button' class='button' onclick="javascript:test_email()" value='{lang_print id=100011042}'>
</div>

<div id="test_email_progress" style="text-align: center; display:none">
<img src="../images/icons/semods_ajaxprogress1.gif">
</div>
</td></tr>

</table>


<br>


<input type='submit' class='button' value='{lang_print id=100011008}'>
<input type='hidden' name='emailer_prev_state' value='{$setting_emailer_enabled}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}