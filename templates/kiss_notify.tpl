{if $setting.setting_kiss_enabled}
{if ($user->level_info.level_kiss_allow == 1)}


{if $total_kiss_requests > 0}
 <div style='margin-bottom: 5px;'><img src='./images/icons/kiss16.gif' border='0' class='icon'><a href="javascript:void(0);" onClick="showhide('kiss_home');">{lang_print id=90000098} {$total_kiss_requests} {lang_print id=90000099}{$kiss_count}</a></div>
{/if}
 <div class='faq' style='display: none;' id='kiss_home'>
{if $total_kiss_requests > 0}
<font align='left'><b>{lang_print id=90000100}</b></font><br><br>
{section name=kiss_loop loop=$kissreq}
<div style="text-align:center; padding-bottom:10px;"><b><a href='{$url->url_create('profile', $kissreq[kiss_loop]->user_info.user_username)}'>{$kissreq[kiss_loop]->user_info.user_username}</a></b><br>
{if $kissreq[kiss_loop]->user_photo('./images/nophoto.gif') != './images/nophoto.gif'}
<a href='{$url->url_create('profile', $kissreq[kiss_loop]->user_info.user_username)}'><img src='{$kissreq[kiss_loop]->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($kissreq[kiss_loop]->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a><br>
{/if}
<br>
<a href='user_kiss_confirm.php?user={$kissreq[kiss_loop]->user_info.user_username}&task=kiss_back'>{lang_print id=90000101}</a> | <a href='user_kiss_confirm.php?user={$kissreq[kiss_loop]->user_info.user_username}&task=remove'>{lang_print id=90000102}</a></div>
{/section}
{/if}</div>
{/if}
{/if}
