{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_recommended_settings.php'>{lang_print id=11140112}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='recommended_top_recommendees.php'>{lang_print id=11140110}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='recommended_top_recommenders.php'>{lang_print id=11140111}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/recommended_vote48.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=11141004} <a href="{$url->url_create('profile', $owner->user_info.user_username)}">{$owner->user_displayname}</a></div>
<div>{lang_print id=11141005}</div>


<div class="recommended_tools">
<a href="{$url->url_create('profile',$owner->user_info.user_username)}">{lang_print id=11141012}</a>
</div>

{if $is_error == 1}
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td class='error'><img src='./images/error.gif' border='0' class='icon'>{$error_message}</td>
  </tr>
  </table>
  <br>
{/if}

<form action='user_recommended_add.php' method='POST'>
<input type='hidden' name='user' value='{$owner->user_info.user_username}' />
<input type='hidden' name='task' value='dosave' />
<table cellpadding='0' cellspacing='0'>
  <tr>
    <td class='form1'>{lang_print id=11141006}</td>
    <td class='form2' valign='bottom'><b><a href='{$url->url_create('profile',$owner->user_info.user_username)}'>{$owner->user_displayname}</a></b></td>
  </tr>
  <tr>
    <td class='form1' valign='top'>{lang_print id=11141007}</td>
    <td class='form2' valign='top'><textarea name='recommended_comment' class='text' rows='5' cols='60'></textarea></td>
  </tr>
  <tr>
    <td class='form1' valign='top'>&nbsp;</td>
    <td class='form2' valign='top'><input type='submit' class='button' value='{lang_print id=11141008}' /></td>
  </tr>  
</table>

{include file='footer.tpl'}
