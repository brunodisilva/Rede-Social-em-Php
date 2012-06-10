{include file='header.tpl'}

<div class='page_header'>{lang_print id=11130403} <a href='{$url->url_create('profile', $owner->user_info.user_username)}'>{$owner->user_info.user_username}</a></div>

<br>

<table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 5px;'>
<tr>
<td>
<a href='{$url->url_create('profile', $owner->user_info.user_username)}'><img src='./images/icons/back16.gif' class='icon' border='0'>{lang_print id=11130405} {$owner->user_info.user_username}{lang_print id=11130406}</a>
</td>
{* DISPLAY PAGINATION MENU IF APPLICABLE *}
{if $maxpage > 1}
  <td align='right'>
    {if $p != 1}<a href='commented.php?user={$owner->user_info.user_username}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=11130407}</a>{else}<font class='disabled'>&#171; {lang_print id=11130407}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=11130408} {$p_start} {lang_print id=11130409} {$total_commented_users} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=11130410} {$p_start}-{$p_end} {lang_print id=11130409} {$total_commented_users} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='commented.php?user={$owner->user_info.user_username}&p={math equation='p+1' p=$p}'>{lang_print id=11130411} &#187;</a>{else}<font class='disabled'>{lang_print id=11130411} &#187;</font>{/if}
    </div>
  </td>
{/if}
</tr>
</table>

{foreach from=$commented_users item=comment_user}
<div>
  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td class='profile_item1' width='70'>
        <a href='{$url->url_create('profile',$comment_user->user_info.user_username)}'><img src='{$comment_user->user_photo('./images/nophoto.gif')}' class='photo' border='0' width='{$misc->photo_size($comment_user->user_photo('./images/nophoto.gif'),'75','75','w')}'><br><b>{$comment_user->user_info.user_username}</b></a>
      </td>
      <td class='profile_item2'>
        <table cellpadding='0' cellspacing='0' width='100%'>
        {foreach from=`$comment_user->comments` item=comment}
          <tr>
          <td class='profile_comment_author'>{$datetime->cdate("`$setting.setting_timeformat` `$commented2` `$setting.setting_dateformat`", $datetime->timezone($comment.comment_date, $global_timezone))}</td>
          </tr>
          <tr>
          <td class='profile_comment_body'>{$comment.comment_body}</td>
          </tr>
        {/foreach}
        </table>
      </td>
    </tr>
  </table>
</div>  
{foreachelse}
    <br>
    <table cellpadding='0' cellspacing='0' align='center'>
    <tr>
    <td class='result'><img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=11130404}</td>
    </tr>
    </table>
{/foreach}


<table cellpadding='0' cellspacing='0' width='100%' style='margin-top: 5px;'>
<tr>
<td>&nbsp;</td>
{* DISPLAY PAGINATION MENU IF APPLICABLE *}
{if $maxpage > 1}
  <td align='right'>
    {if $p != 1}<a href='commented.php?user={$owner->user_info.user_username}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=11130407}</a>{else}<font class='disabled'>&#171; {lang_print id=11130407}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=11130408} {$p_start} {lang_print id=11130409} {$total_commented_users} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=11130410} {$p_start}-{$p_end} {lang_print id=11130409} {$total_commented_users} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='commented.php?user={$owner->user_info.user_username}&p={math equation='p+1' p=$p}'>{lang_print id=11130411} &#187;</a>{else}<font class='disabled'>{lang_print id=11130411} &#187;</font>{/if}
    </div>
  </td>
{/if}
</tr>
</table>


{include file='footer.tpl'}