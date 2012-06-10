
{* $Id: footer_chat.tpl 6 2009-01-11 06:01:29Z john $ *}

{if !$global_smoothbox && $user->user_exists && $user->level_info.level_im_allow}
  
  {lang_javascript ids=3500013,3500014,3510001,3510002 range=3510015-3510038}
  
  {* MAKE SURE TIME IS GETTING ASSIGNED *}
  {if empty($server_time)}{php}$this->assign('server_time',time());{/php}{/if}
  
  {literal}
  <script type="text/javascript">
    
    var SEIM, SEIM_GUI;
    var SEIM_Config = {
      chatEnabled: use_seIM,
      IMEnabled: use_seChat,
      
      imagePath: './images',
      timeDelta: {/literal}{$server_time}{literal} - Math.round((new Date()).getTime() / 1000),
      updateDelay: {/literal}{$setting.setting_chat_update|default:2000}{literal}
    }
    
    window.addEvent('load', function()
    {
      SEIM = new InstantMessengerCore(SEIM_Config);
      SEIM.RegisterModule('gui', (new InstantMessengerGUI));
      SEIM.RegisterModule('conversations', (new InstantMessengerConversations));
      SEIM.Boot();
    });
    
  </script>
  {/literal}
  
  
  {* AUDIO FRAME *}
  <iframe src="about:blank" id="seIMAudioFrame" style="visibility: hidden;" frameborder='0' height="1" width="1"></iframe>
  
  
  {* TRAY TEMPLATE *}
  <div style="display:none;" id="seIM_tray_template">
    <table class="seIM_tray_wrapper" cellpadding="0" cellspacing="0"><tr><td class="seIM_tray_wrapperCell">
      <table class="seIMHide seIM_tray" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="seIM_trayRow">
            <td class="seIM_traySpacer" style="width:99%;">&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </td></tr></table>
  </div>
  
  
  {* CONVERSATION TRAY ITEM TEMPLATE *}
  <div style="display:none;" id="seIM_conversation_trayItem_template">
    <table><tr><tbody>
      <td class="seIM_trayItem seIM_conversation_trayItem" nowrap="nowrap" style="width:140px;" onmouseover="if( !$(this).hasClass('seIM_trayItem_isHover') ) $(this).addClass('seIM_trayItem_isHover');" onmouseout="if( $(this).hasClass('seIM_trayItem_isHover') ) $(this).removeClass('seIM_trayItem_isHover');">
        <a class="seIM_trayItem_menuActivator" href="javascript:void(0);" nowrap="nowrap" style="width: 140px;" onClick='this.blur()'>
          <span class="seIM_trayItem_icon"></span>
          <span class="seIM_trayItem_title">{lang_print id=3510002}</span>
          <span class="seIM_trayItem_userStatus"></span>
        </a>
      </td>
    </tbody></tr></table>
  </div>
  
  
  {* CONVERSATION TRAY MENU TEMPLATE *}
  <div style="display:none;" id="seIM_conversation_trayMenu_template">
    <table class="seIMHide seIM_trayMenu seIM_conversation_trayMenu" style='border-bottom: 0px;' cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td class="seIM_trayMenu_header">
            <table cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td class="seIM_trayMenu_title">
                    <img src='./images/icons/chat_conversation16.png' border='0' style='vertical-align: middle; margin-right: 2px; width: 16px;'>
                    <span class="seIM_trayMenu_userName">{lang_print id=3510002}</span>
                    {* <span class="seIM_trayMenu_userStatus"></span> *}
                  </td>
                  <td class="seIM_trayMenu_buttons">
                    <a class="seIM_trayItem_menuDeactivator" href="javascript:void(0);" onClick='this.blur()'><img src="./images/icons/chat_im_minimize.gif" width='10' height='10' onMouseOver="this.src='./images/icons/chat_im_minimize1.gif'" onMouseOut="this.src='./images/icons/chat_im_minimize.gif'"/></a>
                    <a class="seIM_trayItem_menuDestroyer" href="javascript:void(0);" onClick='this.blur()'><img src="./images/icons/chat_im_close.gif" width='10' height='10'  onMouseOver="this.src='./images/icons/chat_im_close1.gif'" onMouseOut="this.src='./images/icons/chat_im_close.gif'"/></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td class="seIM_trayMenu_body">
            <div class="seIM_trayMenu_bodyListWrapper seIM_conversation_trayMenu_bodyListWrapper">
              <div class="seIM_conversation_trayMenu_bodyListWrapper2">
                <ul class="seIM_trayMenu_bodyList">
                  <li class="seIMNullMessage">{lang_print id=3510019}</li>
                </ul>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td class="seIM_trayMenu_input">
            <input type="text" class="seIM_conversation_trayMenu_textInput" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  
  
  {* CONVERSATION TRAY MENU MESSAGE TEMPLATE *}
  <div style="display:none;" id="seIM_conversation_trayMenu_message_template">
    <ul>
      <li class="seIM_conversation_trayMenu_message">
        <span class="seIM_conversation_trayMenu_messageTimestamp"></span>
        <span class="seIM_conversation_trayMenu_messageUserName"></span>
        <span class="seIM_conversation_trayMenu_messageContent"></span>
      </li>
    </ul>
  </div>
  
  
  {* FRIENDS TRAY ITEM TEMPLATE *}
  <div style="display:none;" id="seIM_friends_trayItem_template">
    <table><tbody><tr>
      <td class="seIM_trayItem seIM_friends_trayItem" nowrap="nowrap" onmouseover="if( !$(this).hasClass('seIM_trayItem_isHover') ) $(this).addClass('seIM_trayItem_isHover');" onmouseout="if( $(this).hasClass('seIM_trayItem_isHover') ) $(this).removeClass('seIM_trayItem_isHover');">
        <a class="seIM_trayItem_menuActivator" href="javascript:void(0);" onClick='this.blur()'>
          <img src="./images/icons/chat_im_friendsMenu16.png" style="width: 16px; height: 16px; margin-right: 2px;" />
          {lang_print id=3510041} (<span class="seIM_friends_trayItem_userCount">0</span>)
        </a>
      </td>
    </tr></tbody></table>
  </div>
  
  
  {* FRIENDS TRAY MENU TEMPLATE *}
  <div style="display:none;" id="seIM_friends_trayMenu_template">
    <table class="seIMHide seIM_trayMenu seIM_friends_trayMenu" cellpadding="0" cellspacing="0">
      <tbody>
        <tr style="padding: 0px;">
          <td class="seIM_trayMenu_header">
            <table cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td class="seIM_trayMenu_title"><div class='seIM_friends_title'>{lang_print id=3510024}</div></td>
                  <td class="seIM_trayMenu_buttons">
                    <a href="javascript:void(0);" class="seIM_trayItem_menuDeactivator"><img src="./images/icons/chat_im_minimize.gif"  onMouseOver="this.src='./images/icons/chat_im_minimize1.gif'" onMouseOut="this.src='./images/icons/chat_im_minimize.gif'"/></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <div class="seIM_trayMenu_bodyListWrapper seIM_friends_trayMenu_bodyListWrapper">
              <ul class="seIM_trayMenu_bodyList">
                <li class="seIMNullMessage">{lang_print id=3510025}</li>
              </ul>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  
  
  {* FRIENDS TRAY MENU BODY LIST ITEM TEMPLATE *}
  <div style="display:none;" id="seIM_friends_trayMenu_bodyListItem_template">
    <ul>
      <li class="seIM_trayMenu_bodyListItem seIM_friends_trayMenu_bodyListItem">
        <table class="seIM_trayMenu_bodyList_activator seIMTips1" cellpadding="0" cellspacing="0" onmouseover="if( !$(this).hasClass('seIM_friends_trayMenu_bodyList_activatorHover') ) $(this).addClass('seIM_friends_trayMenu_bodyList_activatorHover');" onmouseout="if( $(this).hasClass('seIM_friends_trayMenu_bodyList_activatorHover') ) $(this).removeClass('seIM_friends_trayMenu_bodyList_activatorHover');">
          <tr>
            <td rowspan="2" class="seIM_friends_trayMenu_friendIcon" width="25"></td>
            <td class="seIM_friends_trayMenu_friendName"></td>
            <td class="seIM_friends_trayMenu_friendStatus">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="seIM_friends_trayMenu_friendMessage" nowrap="nowrap"></td>
          </tr>
        </table>
      </li>
    </ul>
  </div>

{* APPLICATIONS TRAY ITEM TEMPLATE *}
<div style="display:none;" id="seIM_applications_trayItem_template">
<table><tbody><tr>
<td class="seIM_trayItem_left seIM_applications_trayItem" nowrap="nowrap" onmouseover="if( !$(this).hasClass('seIM_trayItem_isHover') ) $(this).addClass('seIM_trayItem_isHover');" onmouseout="if( $(this).hasClass('seIM_trayItem_isHover') ) $(this).removeClass('seIM_trayItem_isHover');">
<a class="seIM_trayItem_menuActivator" href="javascript:void(0);" onClick='this.blur()'>
<img src="./images/icons/up.png" style="width: 16px; height: 16px; margin-right: 2px;" />
User Menu
</a>
</td>
</tr></tbody></table>
</div>


{* APPLICATIONS TRAY MENU TEMPLATE *}
<div style="display:none;" id="seIM_applications_trayMenu_template">
<table class="seIMHide seIM_trayMenu seIM_applications_trayMenu" cellpadding="0" cellspacing="0">
<tbody>
<tr style="padding: 0px;">
<td class="seIM_trayMenu_header">
<table cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td class="seIM_trayMenu_title"><div class='seIM_applications_title'>User Menu</div></td>
<td class="seIM_trayMenu_buttons">
<a href="javascript:void(0);" class="seIM_trayItem_menuDeactivator"><img src="./images/icons/chat_im_minimize.gif" onMouseOver="this.src='./images/icons/chat_im_minimize1.gif'" onMouseOut="this.src='./images/icons/chat_im_minimize.gif'"/></a>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td>
<div class="seIM_trayMenu_bodyListWrapper seIM_applications_trayMenu_bodyListWrapper">
<div style="text-align:left;margin:10px 0 0 10px;"><!-- Start side Menu-->
{* SHOW WHATS NEW MENU ITEM *}
	<p style='font-size:1.3em'><img src='./images/icons/menu_home.gif' border='0' class='menu_icon'>{lang_print id=1161}</p>
		<ul style='margin:0px;padding:5px 0 10px 20px'>
			<li><a href='user_home.php'>My Home</a></li>
			<li><a href='home.php'>Network's Homepage</a></li>
			<li><a href='network.php'>{lang_print id=1162}</a></li>
		</ul>

{* SHOW PROFILE MENU ITEM *}	
	<p style='font-size:1.3em;'><img src='./images/icons/profile16.gif' border='0' class='app_im_ico'>{lang_print id=652}</p>
		<ul style='margin:0px;padding:5px 0 10px 20px'>
			<li><a href='{$url->url_create('profile', $user->user_info.user_username)}'>View Your Profile</a></li>
			<li><a href='user_editprofile.php'>{lang_print id=1163}</a></li>
			<li><a href='user_editprofile_photo.php'>{lang_print id=1164}</a></li>
				{if $user->level_info.level_profile_style != 0 || $user->level_info.level_profile_style_sample != 0}
			<li><a href='user_editprofile_style.php'>{lang_print id=1165}</a></li>	
				{/if}
		</ul>

{* SHOW MESSAGES MENU ITEM IF ENABLED *}
	{if $user->level_info.level_message_allow != 0}
		<p style='font-size:1.3em'><img src='./images/icons/message_inbox16.gif' border='0' class='app_im_ico'>{lang_print id=654}</p>
			<ul style='margin:0px;padding:5px 0 10px 20px'>
				<li><a href="javascript:TB_show('{lang_print id=784}', 'user_messages_new.php?TB_iframe=true&height=400&width=450', '', './images/trans.gif');">{lang_print id=1167}</a></li>
				<li><a href='user_messages.php'>{lang_print id=1168}{if $user_unread_pms != 0} ({$user_unread_pms}){/if}</a></li>
				<li><a href='user_messages_outbox.php'>{lang_print id=1169}</a></li>
			</ul>
	{/if}

{* SHOW FRIENDS MENU ITEM IF ENABLED *}
    {if $setting.setting_connection_allow != 0}
		<p style='font-size:1.3em'><img src='./images/icons/friends16.gif' border='0' class='app_im_ico'>{lang_print id=653}</p>
			<ul style='margin:0px;padding:5px 0 10px 20px'>
				<li><a href='user_friends.php'>{lang_print id=1170}</a></li>
				<li><a href='user_friends_requests.php'>{lang_print id=1171}</a></li>
				<li><a href='user_friends_requests_outgoing.php'>{lang_print id=1172}</a></li>
			</ul>
    {/if}

{* SHOW SETTINGS MENU ITEM *}
	<p style='font-size:1.3em'><img src='./images/icons/settings16.gif' border='0' class='app_im_ico'>{lang_print id=655}</p>
		<ul style='margin:0px;padding:5px 0 10px 20px'>
			<li><a href='user_account.php'>{lang_print id=1173}</a></li>
			<li><a href='user_account_privacy.php'>{lang_print id=1174}</a></li>
		</ul>	
	
</div><!-- End side Menu-->
</div>
</td>
</tr>
</tbody>
</table>
</div>  
  
  {* OPTIONS TRAY ITEM TEMPLATE *}
  <div style="display:none;" id="seIM_options_trayItem_template">
    <table><tbody><tr>
      <td class="seIM_trayItem seIM_options_trayItem" width="25" nowrap="nowrap" onmouseover="if( !$(this).hasClass('seIM_trayItem_isHover') ) $(this).addClass('seIM_trayItem_isHover');" onmouseout="if( $(this).hasClass('seIM_trayItem_isHover') ) $(this).removeClass('seIM_trayItem_isHover');">
        <a class="seIM_trayItem_menuActivator" href="javascript:void(0);" onClick='this.blur()'>
          <img class="seIM_trayItem_icon" src="./images/status_im/options_offline16.png"  style="width: 16px; height: 16px;" />
        </a>
      </td>
    </tr></tbody></table>
  </div>
  
  
  {* OPTIONS TRAY MENU TEMPLATE *}
  <div style="display:none;" id="seIM_options_trayMenu_template">
    <table class="seIMHide seIM_trayMenu seIM_options_trayMenu" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td class="seIM_trayMenu_header">
            <table cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td class="seIM_trayMenu_title"><div class='seIM_options_title'>{lang_print id=3510026}</div></td>
                  <td class="seIM_trayMenu_buttons">
                    <a href="javascript:void(0);" class="seIM_trayItem_menuDeactivator"><img src="./images/icons/chat_im_minimize.gif"  onMouseOver="this.src='./images/icons/chat_im_minimize1.gif'" onMouseOut="this.src='./images/icons/chat_im_minimize.gif'"/></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <div class="seIM_trayMenu_bodyListWrapper seIM_options_trayMenu_bodyListWrapper">
              <ul class="seIM_trayMenu_bodyList">
                <li>
                  <div style='margin-bottom: 5px;'>
                    <div>{lang_print id=3510039}</div>
                    <select class="seIM_options_trayMenu_statusSelect" onchange="this.blur();">
                      <option value="1" class="seIM_options_trayMenu_status_1">{lang_print id=3510028}</option>
                       <option value="0" class="seIM_options_trayMenu_status_0">{lang_print id=3510027}</option> 
                      <option value="2" class="seIM_options_trayMenu_status_2">{lang_print id=3510029}</option>
                      {* <option value="3" class="seIM_options_trayMenu_status_3">{lang_print id=3510030}</option> *}
                    </select>
                  </div>
                </li>
                <li>
                  {* <img class="seIM_options_trayMenu_audioButton" src="./images/icons/chat_audio2.gif" style="width: 16px;" /> *}
                  <table cellpadding='0' cellspacing='0'><tr><td><input type="checkbox" class="seIM_options_trayMenu_audioButton" /></td><td>&nbsp;{lang_print id=3510040}</td></tr></table>
                  
                  {* TEMPORARILY REMOVED *}
                  <table cellpadding='0' cellspacing='0' style="display: none;"><tr><td><img class="seIM_options_trayMenu_timestampButton" src="./images/icons/chat_clock2.gif" style="width: 16px;" /></td><td>&nbsp;&nbsp;</td></tr></table>
                </li>
              </ul>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
{/if}