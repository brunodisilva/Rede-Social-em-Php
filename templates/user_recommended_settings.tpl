{include file='header.tpl'}


<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_recommended_settings.php'>{lang_print id=11140112}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='recommended_top_recommendees.php'>{lang_print id=11140110}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='recommended_top_recommenders.php'>{lang_print id=11140111}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/recommended_vote48.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=11141201}</div>
<div>{lang_print id=11141202}</div>


<div class="recommended_tools">
<a href="recommended_recommenders.php?user={$user->user_info.user_username}">{lang_print id=11141208}</a>
<a href="recommended_recommendees.php?user={$user->user_info.user_username}">{lang_print id=11141209}</a>
</div>

<br>

{* SHOW SUCCESS MESSAGE *}
{if $result != 0}
  <table cellpadding='0' cellspacing='0'><tr><td class='result'>
  <div class='success'><img src='./images/success.gif' border='0' class='icon'> {lang_print id=11141203}</div>
  </td></tr></table>
{/if}

<br>

<form action='user_recommended_settings.php' method='POST'>

<div><b>{lang_print id=11141205}</b></div>
<div class='form_desc'>{lang_print id=11141206}</div>

{if $user->level_info.level_recommended_allow != 0}
  <table cellpadding='0' cellspacing='0'>
  <tr><td><input type='checkbox' value='1' id='recommendedcomment' name='usersetting_notify_recommendedcomment'{if $user->usersetting_info.usersetting_notify_recommendedcomment == 1} CHECKED{/if}></td><td><label for='recommendedcomment'>{lang_print id=11141207}</label></td></tr>
  </table>
{/if}

<br>

<input type='submit' class='button' value='{lang_print id=11141204}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='footer.tpl'}