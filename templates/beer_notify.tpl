{if $setting.setting_beers_enabled}
{if ($user->level_info.level_beers_allow == 1)}


{if $total_beer_requests > 0}
 <div style='margin-bottom: 5px;'><img src='./images/icons/beers16.gif' border='0' class='icon'><a href="javascript:void(0);" onClick="showhide('beers_home');">{lang_print id=16000098} {$total_beer_requests} {lang_print id=16000099}{$beer_count}</a></div>
{/if}
 <div class='faq' style='display: none;' id='beers_home'>
{if $total_beer_requests > 0}
<font align='left'><b>{lang_print id=16000100}</b></font><br><br>
{section name=beer_loop loop=$beersreq}
<div style="text-align:center; padding-bottom:10px;"><b><a href='{$url->url_create('profile', $beersreq[beer_loop]->user_info.user_username)}'>{$beersreq[beer_loop]->user_info.user_username}</a></b><br>
{if $beersreq[beer_loop]->user_photo('./images/nophoto.gif') != './images/nophoto.gif'}
<a href='{$url->url_create('profile', $beersreq[beer_loop]->user_info.user_username)}'><img src='{$beersreq[beer_loop]->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($beersreq[beer_loop]->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a><br>
{/if}
<br>
<a href='user_beers_confirm.php?user={$beersreq[beer_loop]->user_info.user_username}&task=beer_back'>{lang_print id=16000101}</a> | <a href='user_beers_confirm.php?user={$beersreq[beer_loop]->user_info.user_username}&task=remove'>{lang_print id=16000102}</a></div>
{/section}
{/if}</div>
{/if}
{/if}
