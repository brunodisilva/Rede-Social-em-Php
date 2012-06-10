{if $setting.setting_winks_enabled}
{if ($user->level_info.level_winks_allow == 1)}


{if $total_wink_requests > 0}
 <div style='margin-bottom: 5px;'><img src='./images/icons/winks16.gif' border='0' class='icon'><a href="javascript:void(0);" onClick="showhide('winks_home');">{lang_print id=14000098} {$total_wink_requests} {lang_print id=14000099}{$wink_count}</a></div>
{/if}
 <div class='faq' style='display: none;' id='winks_home'>
{if $total_wink_requests > 0}
<font align='left'><b>{lang_print id=14000100}</b></font><br><br>
{section name=wink_loop loop=$winksreq}
<div style="text-align:center; padding-bottom:10px;"><b><a href='{$url->url_create('profile', $winksreq[wink_loop]->user_info.user_username)}'>{$winksreq[wink_loop]->user_info.user_username}</a></b><br>
{if $winksreq[wink_loop]->user_photo('./images/nophoto.gif') != './images/nophoto.gif'}
<a href='{$url->url_create('profile', $winksreq[wink_loop]->user_info.user_username)}'><img src='{$winksreq[wink_loop]->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($winksreq[wink_loop]->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a><br>
{/if}
<br>
<a href='user_winks_confirm.php?user={$winksreq[wink_loop]->user_info.user_username}&task=wink_back'>{lang_print id=14000101}</a> | <a href='user_winks_confirm.php?user={$winksreq[wink_loop]->user_info.user_username}&task=remove'>{lang_print id=14000102}</a></div>
{/section}
{/if}</div>
{/if}
{/if}
