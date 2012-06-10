{include file='admin_header.tpl'}

<h2>{lang_print id=100016108}</h2>
{lang_print id=100016109}

<br><br>

{if $result != 0}

  {if empty($error)}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=100016110}</div>
  {else}
    <div class='error'><img src='../images/error.gif' class='icon' border='0'> {$error} </div>
  {/if}

{/if}


<form action='admin_userpoints.php' method='POST'>


  <table cellpadding='0' cellspacing='0' width='600px'>
  <tr><td class='header'>{lang_print id=100016111}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016112}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='setting_userpoints_enable_topusers' id='setting_userpoints_enable_topusers_1' value='1'{if $setting_userpoints_enable_topusers == 1} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_topusers_1'>{lang_print id=100016113}</label></td></tr>
    <tr><td><input type='radio' name='setting_userpoints_enable_topusers' id='setting_userpoints_enable_topusers_0' value='0'{if $setting_userpoints_enable_topusers == 0} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_topusers_0'>{lang_print id=100016114}</label></td></tr>
    </table>
  </td></tr>
  
  </table>

<br>

  <table cellpadding='0' cellspacing='0' width='600px'>
  <tr><td class='header'>{lang_print id=100016116}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016117}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='setting_userpoints_enable_offers' id='setting_userpoints_enable_offers_1' value='1'{if $setting_userpoints_enable_offers == 1} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_activitypoints_1'>{lang_print id=100016118}</label></td></tr>
    <tr><td><input type='radio' name='setting_userpoints_enable_offers' id='setting_userpoints_enable_offers_0' value='0'{if $setting_userpoints_enable_offers == 0} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_activitypoints_0'>{lang_print id=100016119}</label></td></tr>
    </table>
  </td></tr>
  
  </table>

<br>

  <table cellpadding='0' cellspacing='0' width='600px'>
  <tr><td class='header'>{lang_print id=100016120}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016121}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='setting_userpoints_enable_shop' id='setting_userpoints_enable_shop_1' value='1'{if $setting_userpoints_enable_shop == 1} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_activitypoints_1'>{lang_print id=100016122}</label></td></tr>
    <tr><td><input type='radio' name='setting_userpoints_enable_shop' id='setting_userpoints_enable_shop_0' value='0'{if $setting_userpoints_enable_shop == 0} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_activitypoints_0'>{lang_print id=100016123}</label></td></tr>
    </table>
  </td></tr>
  
  </table>

<br>

  <table cellpadding='0' cellspacing='0' width='600px'>
  <tr><td class='header'>{lang_print id=100016124}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016125}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='setting_userpoints_enable_statistics' id='setting_userpoints_enable_statistics_1' value='1'{if $setting_userpoints_enable_statistics == 1} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_statistics_1'>{lang_print id=100016126}</label></td></tr>
    <tr><td><input type='radio' name='setting_userpoints_enable_statistics' id='setting_userpoints_enable_statistics_0' value='0'{if $setting_userpoints_enable_statistics == 0} CHECKED{/if}>&nbsp;</td><td><label for='setting_userpoints_enable_statistics_0'>{lang_print id=100016127}</label></td></tr>
    </table>
  </td></tr>
  
  </table>

<br>


<input type='submit' class='button' value='{lang_print id=100016115}'>
<input type='hidden' name='crontab_prev_state' value='{$setting_crontab_enabled}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}