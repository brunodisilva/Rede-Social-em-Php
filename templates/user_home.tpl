{include file='header.tpl'}
{literal}<script type="text/javascript" src="./include/js/ajaxtabs.js"></script>{/literal}
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='home_left'>

{*----------ANNOUNCEMENTS--------*}
{if $news|@count > 0}
<div style="width:600px; margin-bottom:10px;">
<div style="background-image:url(./images/ann_top.gif); background-repeat:no-repeat; width:600px; height:10px;"></div>
<div style="background-image:url(./images/ann_middle.gif); background-repeat:repeat-y; width:600px; padding:0 15px 0 15px;">

{section name=news_loop loop=$news max=1}
			<div style="font-weight:bold; font-size:11pt; color:#333333;">
			<b>{$news[news_loop].announcement_subject}</b> {$news[news_loop].announcement_date}
			</div>
			<div style="padding-right:15px;">{$news[news_loop].announcement_body}</div>
{/section}

</div>
<div style="background-image:url(./images/ann_bottom.gif); background-repeat:no-repeat; height:12px; "></div>
</div>
{/if}
{*----------ANNOUNCEMENTS--------*}

{*-----------STATUS--------------*}
{if $user->user_exists == 1}
<table style="font-size:13px; " width="500" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50" align="left">
<div style="margin:0px 10px 0px 10px;"><a href='{$url->url_create('profile', $user->user_info.user_username)}'><img class="photo" src='{$user->user_photo('./images/nophoto.gif', TRUE)}' width='50' height='50' border='0'></a></div>
		</td>
    <td width="">

  {if $user->level_info.level_profile_status != 0}
    {literal}
    <script type="text/javascript">
    <!-- 
      var current_status = '{/literal}{$user->user_info.user_status}{literal}';
      function noenter_status(e) { 
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	if(keycode == 13) {
	  changeStatus_submit();
	  return false;
	}
      }
      function changeStatus() {
        var HTML = "{/literal}<div id='ajax_status'><div style='padding-top:5px;'><div style='float:left; padding:5px 5px 5px 10px; background-image:url(./images/inputback.gif); background-repeat:repeat-x; width:398px; font-size:13px; color:#aaaaaa;'> {$user->user_displayname_short}{literal} <input type='text' name='status_new' id='status_new' maxlength='100' value='";
	if(current_status == '') { HTML += "{/literal}{lang_print id=744}{literal}"; } else { HTML += current_status.replace(/<wbr>/g, '').replace(/&shy;/g, '');; }
        HTML += "' style='width:225px; background:#ffffff; border:none;' onkeypress='return noenter_status(event)'><a href='javascript:changeStatus_submit();'>{/literal}{lang_print id=746}{literal}</a> | <a href='javascript:changeStatus_return();'>{/literal}{lang_print id=747}{literal}</a></div></div></div>";
	$('ajax_status').innerHTML = HTML;
	$('status_new').focus();
	$('status_new').select();
      }
      function changeStatus_return() {
	if(current_status == '') {
	  $('ajax_status').innerHTML = "<a href='javascript:changeStatus();'>{/literal}<div style='padding-top:5px;'><div style='padding:5px 5px 5px 10px; background-image:url(./images/inputback.gif); background-repeat:repeat-x; width:398px; font-size:13px; color:#aaaaaa;'>{lang_print id=743}</div>{literal}</a>";
	} else {
	  $('ajax_status').innerHTML = "{/literal}<div id='ajax_status'>{$user->user_displayname_short} <span id='ajax_currentstatus_value'><span id='ajax_currentstatus_value'>"+current_status+"</span></span> <font style='font-size:9px; color:#aaaaaa;'>{lang_sprintf id=773 1=1}</font> <a href='javascript:void(0);' onClick='changeStatus()'><div style='padding-top: 5px;'><div style='float:left; padding:5px 5px 5px 10px; background-image:url(./images/inputback.gif); background-repeat:repeat-x; width:398px; font-size:13px; color:#aaaaaa;'>{lang_print id=745}</div></a></div></div>{literal}";
	}
      }
      function changeStatus_submit() {
	$('ajaxframe').src = "misc_js.php?task=status_change&status="+encodeURIComponent($('status_new').value);
      }
    //-->
    </script>
    {/literal}

	<div id='ajax_status'>
	  {if $user->user_info.user_status != ""}
	    {assign var='status_date' value=$datetime->time_since($user->user_info.user_status_date)}
	    {$user->user_displayname_short} <span id='ajax_currentstatus_value'>{$user->user_info.user_status}</span> <font style='font-size:9px; color:#aaaaaa;'>{lang_sprintf id=$status_date[0] 1=$status_date[1]}</font>
<a href="javascript:void(0);" onClick="changeStatus()"><div style='padding-top:5px;'><div style="padding:5px 5px 5px 10px; background-image:url(./images/inputback.gif); background-repeat:repeat-x; width:398px; font-size:13px; color:#aaaaaa;'">{lang_print id=745}</div></a></div>
	  {else}
<a href="javascript:changeStatus();"><div style='padding-top:5px;'><div style="padding:5px 5px 5px 10px; background-image:url(./images/inputback.gif); background-repeat:repeat-x; width:398px; font-size:13px; color:#aaaaaa;'">{lang_print id=743}</div></a>
	  {/if}
	</div>

  {/if}
		</td>
	</tr>
</table>
<br>
{/if}
{*-----------STATUS--------------*}

<ul id="countrytabs" class="shadetabs">
<li><a href="#" rel="#default" class="selected">{lang_print id=737}</a></li>
<li><a href="lateststatus.php" rel="countrycontainer">{lang_print id=1177}</a></li>
</ul>

{*-*}
<div id="countrydivcontainer" style="border-top:1px solid #d3dae8; width:575px; margin-bottom:1em; padding:0px 10px 10px 10px; margin-left:10px;">


  <div style='margin-bottom:7px;'>
    <div style='width:50%; float: right; text-align:right; padding-top:5px;'>{if $setting.setting_actions_preference == 1} <a href='javascript:actionprefs();'>{lang_print id=1070}</a>{/if}</div>
    <div style='clear:both;'></div>
  </div>

  {* BEGIN LEFT COLUMN *}

  {* ACTIVITY FEED PREFERENCES *}
  {if $setting.setting_actions_preference == 1}

    {* DIV FOR SPECIFYING ACTION PREFERENCES *}
    <div style='display: none;' id='actionprefs'>
      <div style='margin-top: 10px;'>{lang_print id=1069}</div>
	<br>
        <form action='misc_js.php' method='post' target='ajaxframe'>
        <table cellpadding='0' cellspacing='0'>
        {section name=actiontypes_loop loop=$actiontypes}
	  <tr>
	  <td><label>
 	  <input type='checkbox' name='actiontype[]' id='actiontype_id_{$actiontypes[actiontypes_loop].actiontype_id}' value='{$actiontypes[actiontypes_loop].actiontype_id}'{if $actiontypes[actiontypes_loop].actiontype_selected == 1} checked='checked'{/if}>
          {lang_print id=$actiontypes[actiontypes_loop].actiontype_desc}
	  </label></td>
	  </tr>
        {/section}
	</table>
        <br>
        <input type='submit' class='button' value='{lang_print id=173}'> <input type='button' class='button' value='{lang_print id=39}' onClick='parent.TB_remove();'>
        <input type='hidden' name='task' value='save_actionprefs'>
      </form>
    </div>

    {* JAVACRIPT FOR SPECIFYING ACTION PREFERENCES *}
    {literal}
    <script type="text/javascript">
    <!-- 
    function actionprefs() {
      TB_show('{/literal}{lang_print id=1068}{literal}', '#TB_inline?height=250&width=300&inlineId=actionprefs', '', '../images/trans.gif');
    }
    //-->
    </script>
    {/literal}

  {/if}

      {* SHOW RECENT ACTIVITY LIST *}
      <div class='home_whatsnew'>
      
        {* RECENT ACTIVITY ADVERTISEMENT BANNER *}
        {if $ads->ad_feed != ""}
          <div class='home_action' style='display: block; visibility: visible; padding-bottom: 10px;'>{$ads->ad_feed}</div>
        {/if}
        
     {* DISPLAY ACTIONS *}
        {section name=actions_loop loop=$actions}
          <div id='action_{$actions[actions_loop].action_id}' class='home_action{if $smarty.section.actions_loop.first}_top{/if}'>
            <table cellpadding='0' cellspacing='0'>
              <tr>
                <td valign='top'>
                  <img src='./images/icons/{$actions[actions_loop].action_icon}' border='0' class='icon' />
                </td>
                <td valign='top' width='100%'>
                  {assign var='action_date' value=$datetime->time_since($actions[actions_loop].action_date)}
                  <div class='home_action_date'>{lang_sprintf id=$action_date[0] 1=$action_date[1]}</div>
                  {assign var='action_media' value=''}
                  {if $actions[actions_loop].action_media !== FALSE}{capture assign='action_media'}{section name=action_media_loop loop=$actions[actions_loop].action_media}<a href='{$actions[actions_loop].action_media[action_media_loop].actionmedia_link}'><img src='{$actions[actions_loop].action_media[action_media_loop].actionmedia_path}' border='0' width='{$actions[actions_loop].action_media[action_media_loop].actionmedia_width}' class='recentaction_media'></a>{/section}{/capture}{/if}
                  {lang_sprintf assign='action_text' id=$actions[actions_loop].action_text args=$actions[actions_loop].action_vars}
                  {$action_text|replace:"[media]":$action_media|choptext:50:"<br>"}
                </td>
              </tr>
            </table>
          </div>
        {sectionelse}
          {lang_print id=738}
        {/section}
      </div>
</td>


<div>
{*-*}
{literal}
<script type="text/javascript">
<!-- 
var countries=new ddajaxtabs("countrytabs", "countrydivcontainer")
countries.setpersist(true)
countries.setselectedClassTarget("link") //"link" or "linkparent"
countries.init()
//-->
</script>
{/literal}


{* BEGIN RIGHT COLUMN *}
<td class='home_right'>

{* BEGIN NEW UPDATES*}
{*-------------------*}	
{if $user->user_exists == 1}
<div class="rigth_block_overline">
<div class="right_block_header"></div>
<div class="rigth_block_middle">

		<div class="right_block_content"><b>{lang_print id=1198}</b></div>
      {if $notifys[1] != 0}
        	{section name=notify_loop loop=$notifys[0]}
      <div style='font-weight: bold; padding-top: 5px;' id='notify_{$notifys[0][notify_loop].notifytype_id}_{$notifys[0][notify_loop].notify_grouped}'>
        <img src='./images/icons/{$notifys[0][notify_loop].notify_icon}' border='0' style='border: none; margin: 0px 5px 0px 10px; display: inline; vertical-align: middle;' class='icon'><a href="{$notifys[0][notify_loop].notify_url}">{lang_sprintf id=$notifys[0][notify_loop].notify_desc 1=$notifys[0][notify_loop].notify_total 2=$notifys[0][notify_loop].notify_text[0]}</a></div>
    {/section}
    	{else}
        	<div style="padding-left:10px;">There are currently no new updates for you.</div>
        {/if}
</div>
<div class="right_block_footer"></div>
</div>
{/if}
{*-------------------*}	
{* END NEW UPDATES*}

<div class="rigth_block_overline">
<div class="right_block_header"></div>
<div class="rigth_block_middle">

{* BEGIN APPLICATIONS*}
{*-------------------*}	
	{if $user->user_exists == 1}
    {if $global_plugins.plugin_controls.show_menu_user}
		<div class="right_block_content"><b>{lang_print id=1166}</b></div>
		<div class="right_block_content">
                {foreach from=$global_plugins key=plugin_k item=plugin_v}
                 {if $plugin_v.menu_user != ''} 
	            <div class="right_block_apps"><a href='{$plugin_v.menu_user.file}' class='menu_item'><img src='./images/icons/{$plugin_v.menu_user.icon}' border='0' class='menu_icon2'>{lang_print id=$plugin_v.menu_user.title}</a></div>
               {/if} 
                {/foreach}
		</div>
<img class="right_block_hr" src="./images/hr.gif">
	{/if}
	{/if}
{*-------------------*}	
{* END APPLICATIONS*}

{* BEGIN INVITE*}
{*-------------------*}	
		<div class="right_block_content"><b>{lang_print id=722}</b></div>
		<div class="right_block_content">

<div>
		<div style="float:left;"><img src='./images/invite_home.png' border='0' class='icon'></div>
		<div style="float:left; margin-left:5px; width:175px;">
<a href="invite.php"><b>{lang_print id=722}</b></a><br>
Share the Facecook experience with more of your friends. Use our simple invite tools to start connecting.
		</div>
</div>



		</div>
{*-------------------*}	
{* END INVITE*}

{* BEGIN BIRTHDAYS*}
{*-------------------*}	
<img class="right_block_hr" src="./images/hr.gif">
		<div class="right_block_content"><b>{lang_print id=1176}</b></div>
		<div class="right_block_content">
<div style="width:20px; float:left; margin-right:5px;">
<img src='./images/icons/gift.gif' border='0' class='icon'/>
</div>
<div style="width:295px; float:left;">
  {* SHOW UPCOMING BIRTHDAYS *}
    {section name=birthday_loop loop=$birthdays max=20}
      <div><a href='{$url->url_create('profile', $birthdays[birthday_loop].birthday_user_username)}'>{$birthdays[birthday_loop].birthday_user_displayname}</a> - {$datetime->cdate("M. d", $datetime->timezone($birthdays[birthday_loop].birthday_date, $global_timezone))}</div>
    {sectionelse}
      {lang_print id=1180}
    {/section}
</div>
		</div>
{*-------------------*}	
{* END BIRTHDAYS*}

{* BEGIN STATS*}
{*-------------------*}	
<img class="right_block_hr" src="./images/hr.gif">
		<div class="right_block_content"><b>{lang_print id=739}</b></div>
		<div class="right_block_content" style="width:300px;">
           {* SHOW NUMBER OF TIMES PROFILE HAS BEEN VIEWED *}
            <div>
              <img src='./images/icons/newviews16.gif' border='0' class='icon' />
              {lang_sprintf id=740 1=$profile_views}
              {if $profile_viewers != 0}[ <a href='user_home.php?task=resetviews'>{lang_print id=741}</a> ]{/if}
              <br />
              
              {* WHO VIEWED MY PROFILE LINK *}
              {if $user->user_info.user_saveviews == 1}
              {if $profile_viewers|@count != 0}
                <div style='margin-top: 10px;'>
                  <a href='javascript:void(0);' onClick="$('profile_viewers').style.display='block';this.style.display='none';">{lang_print id=1064}</a>
                  <div id='profile_viewers' style='display: none; max-height:400px; overflow:auto;'>
                    {lang_print id=1182}<br />
                    {section name=viewer_loop loop=$profile_viewers}
                    <a href='{$url->url_create("profile", $profile_viewers[viewer_loop]->user_info.user_username)}'>{$profile_viewers[viewer_loop]->user_displayname}</a>
                    {if $smarty.section.viewer_loop.last !== TRUE}, {/if}{/section}
                  </div>
                </div>
              {else}
                {lang_print id=1063}
              {/if}
              {/if}
            </div>
	 	</div>
{*-------------------*}		
{* END STATS*}

{* BEGIN ONLINE USERS*}
{*-------------------*}	
 {* SHOW ONLINE USERS IF MORE THAN ZERO *}
  {math assign='total_online_users' equation="x+y" x=$online_users[0]|@count y=$online_users[1]}
  {if $total_online_users > 0}
  <img class="right_block_hr" src="./images/hr.gif">
		<div class="right_block_content"><b>{lang_print id=665} ({$total_online_users})</b></div>
		<div class="right_block_content">

    {if $online_users[0]|@count == 0}
      {lang_sprintf id=977 1=$online_users[1]}
    {else}
      {capture assign='online_users_registered'}{section name=online_loop loop=$online_users[0]}{if $smarty.section.online_loop.rownum != 1}, {/if}<a href='{$url->url_create("profile", $online_users[0][online_loop]->user_info.user_username)}'>{$online_users[0][online_loop]->user_displayname}</a>{/section}{/capture}
      {lang_sprintf id=976 1=$online_users_registered 2=$online_users[1]}
    {/if}
		</div>
  {/if}
{*-------------------*}	
{* END ONLINE USERS*}
		
{* INVITE*}
{*-------------------*}	

<img class="right_block_hr" src="./images/hr.gif">
		<div class="right_block_content"><b>{lang_print id=722}</b></div>
		<div class="right_block_content">

<img src='./images/icons/invite.gif' border='0' class='icon'/> <a href="invite.php">{lang_print id=722}</a>

		</div>

{*-------------------*}	
{* END INVITE*}
		
</div>

<div class="right_block_footer"></div>
</div>

</td>
</tr>
</table>

{include file='footer.tpl'}