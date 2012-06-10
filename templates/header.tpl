{* $Id: header.tpl 65 2009-02-20 03:03:23Z john $ *}

{* INCLUDE HEADER CODE *}
{include file="header_global.tpl"}

{if $smarty.const.SE_DEBUG && $admin->admin_exists}{include file="header_debug.tpl"}{/if}

<div id="smoothbox_container"></div>

{* BEGIN CENTERING TABLE *}
<table cellpadding='0' cellspacing='0' class='body' align='center'>
<tr>
<td>

{* START TOPBAR *}
<div class="top_bar">

  {* PAGE-TOP ADVERTISEMENT BANNER *}
  {if $ads->ad_top != ""}
    <div class='ad_top' style='display: block; visibility: visible;'>{$ads->ad_top}</div>
  {/if}

  {* LOGO AND SEARCH *}
<table cellpadding='0' cellspacing='0' style='width:965px; padding-top:1px; height:29.5px;' align='center'>
<tr>
    <td style="background-image:url(./images/top_left.gif); height:29.5px; padding-left:5px; width:4px;" align='left' width="4"></td>
    <td width="auto" align="left">
	
{if $user->user_exists != 1}
	<a href='./'><div class="logo"></div></a>
{/if}

{if $user->user_exists == 1}
	<a href='user_home.php'><div class="logo"></div></a>
{/if}
	
	</td>

{if $user->user_exists != 0}
{*-----------------------------------------------------------------------------------------------------------*}	
	
	  {literal}
    <script type='text/javascript'>
    <!--
    var open_menu;
    var current_timeout = new Array();
    function showMenu(id1) {
      if($(id1)) {
        if($(id1).style.display == 'none') {
	  if($(open_menu)) { hideMenu($(open_menu)); }
          $(id1).style.display='inline';
	  startMenuTimeout($(id1));
	  $(id1).addEvent('mouseover', function(e) { killMenuTimeout(this); });
	  $(id1).addEvent('mouseout', function(e) { startMenuTimeout(this); });
	  open_menu = id1;
        }
      }
    }
    function killMenuTimeout(divEl) {
      clearTimeout(current_timeout[divEl.id]);
      current_timeout[divEl.id] = '';
    }
    function startMenuTimeout(divEl) {
      if(current_timeout[divEl.id] == '') {
        current_timeout[divEl.id] = setTimeout(function() { hideMenu(divEl); }, 1000);
      }
    }
    function hideMenu(divEl) {
      divEl.style.display = 'none'; 
      current_timeout[divEl.id] = '';
      divEl.removeEvent('mouseover', function(e) { killMenuTimeout(this); });
      divEl.removeEvent('mouseout', function(e) { startMenuTimeout(this); });
    }
    //-->
    </script>
    {/literal}
	
	

{*-------------------------------------------*}
	<td width="auto" style="text-align:left;" align="left">
<div class='top_menu_link_container'><div class='top_menu_link'><a href='user_home.php' class='top_menu_item'><div style="height:29px; color:#fff; cursor:pointer;">{lang_print id=645}</div></a>
</div></div>

<div class='top_menu_link_container'><div class='top_menu_link'>
  <a href='{$url->url_create('profile', $user->user_info.user_username)}' class='top_menu_item'><font color="#FFFFFF"><div style="height:29px; color:#fff; cursor:pointer;">{lang_print id=652}</div></font></a>
</div></div>




<div class='top_menu_link_container'><div class='top_menu_link'>
{if $setting.setting_connection_allow != 0}
        <a href='user_friends.php' class='menu_item1' style='vertical-align: middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_friends');"><div style="height:29px; color:#fff;">{lang_print id=653}</div></a>
		 <div>
	        <div class='menu_dropdown3' id='menu_dropdown_friends' style='display:none;'>
              <div>
                <div class='menu_item_dropdown'><a href='user_friends.php' class='menu_item'>{lang_print id=1170}</a></div>
                <div class='menu_item_dropdown'><a href='user_friends_requests.php' class='menu_item'>{lang_print id=1171}</a></div>
                <div class='menu_item_dropdown'><a href='user_friends_requests_outgoing.php' class='menu_item'>{lang_print id=1172}</a></div>
              </div>
	        </div>
	     </div>
    {/if}
</div></div>

{literal}
<!--[if lte IE 7]>
<style type="text/css">
div.menu_dropdown {
	margin-top:-5px;
	margin-left:-6px;
}

div.menu_dropdown2 {
	margin-top:-5px;
	margin-left:-5px;
}

div.menu_dropdown3 {
	margin-top:-5px;
	margin-left:-6px;
}

div.menu_dropdown_browse {
	margin-top:-5px;
	margin-left:-6px;
}

div.menu_dropdown_messages {
	margin-top:-5px;
	margin-left:-6px;
}
</style>
<![endif]-->
{/literal}


    {* SHOW MESSAGES MENU ITEM IF ENABLED *}
    {if $user->level_info.level_message_allow != 0}
<div class='top_menu_link_container'><div class='top_menu_link'>

          <a href='user_messages.php'  class='menu_item1' style='vertical-align:middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_messages');"><div style="height:29px; color:#fff;">{lang_print id=654}</div></a>
		  <div>
	        <div class='menu_dropdown_messages' id='menu_dropdown_messages' style='display:none;'>
              <div>
                <div class='menu_item_dropdown'><a href="javascript:TB_show('{lang_print id=784}', 'user_messages_new.php?TB_iframe=true&height=400&width=450', '', './images/trans.gif');" class='menu_item'>{lang_print id=1167}</a></div>
              </div>
            </div>
          </div>

</div></div>
    {/if}

{if $user_unread_pms != 0}
<div class='top_menu_link_container'><div class='top_menu_link'>
    {if $user->level_info.level_message_allow != 0}
         <a href='user_messages.php' class='menu_item1' style='vertical-align:middle; cursor:pointer;'><div style="height:29px; color:#fff;">({$user_unread_pms})</div></a>
    {/if}
</div></div>
{/if}

    {if $global_plugins.plugin_controls.show_menu_user}
<div class='top_menu_link_container'><div class='top_menu_link'>
          <a class='menu_item1' style='vertical-align:middle; cursor:crosshair;' onMouseOver="showMenu('menu_dropdown_browse');"><div style="height:29px; color:#fff;">Browse</div></a>
		  <div>
	        <div class='menu_dropdown_browse' id='menu_dropdown_browse' style='display:none;'>
              <div>
                <div class='menu_item_dropdown'>
				
	{* SHOW ANY PLUGIN MENU ITEMS *}
  {foreach from=$global_plugins key=plugin_k item=plugin_v}
    {if $plugin_v.menu_main != ''}
      <a href='{$plugin_v.menu_main.file}' class='menu_item'>{lang_print id=$plugin_v.menu_main.title}</a>
    {/if} 
  {/foreach}
               </div>
	   		 </div>
          </div>

	
</div></div>
{/if}



	</td>

{*-------------------------------------------*}
	<td width="560">
	
	
<div class='top_menu_link_container3' style="cursor:default;"><div class='top_menu_link2'>
	<form action='search.php' method='post'>
    <input type='text' name='search_text' class='text2' size='25'><input type='submit' class='button2' value=''>
    <input type='hidden' name='task' value='dosearch'>
    <input type='hidden' name='t' value='0'>
    </form>
</div></div>
	
	

<div class='top_menu_link_container2'><div class='top_menu_link' style="font-weight:normal; ">
{include file='fbconnect_header_logout.tpl'}
</div></div>

<div class='top_menu_link_container2'><div class='top_menu_link' style="font-weight:normal; ">
{* SHOW SETTINGS MENU ITEM *}
<a href='user_account.php' class='menu_item1' onMouseOver="showMenu('menu_dropdown_settings');"><div style="height:29px; color:#fff; cursor:pointer;">{lang_print id=655}</div></a>
        <div>
	 	  <div class='menu_dropdown2' id='menu_dropdown_settings' style='display:none;'>
            <div>
              <div class='menu_item_dropdown'><a href='user_account.php' class='menu_item' ><img src='./images/icons/settings16.gif' border='0' class='menu_icon2'>{lang_print id=1173}</a></div>
              <div class='menu_item_dropdown'><a href='user_account_privacy.php' class='menu_item'><img src='./images/icons/settings_privacy16.gif' border='0' class='menu_icon2'>{lang_print id=1174}</a></div>
            </div>
	 	  </div>
        </div>
{* END SETTINGS MENU ITEM *}
</div></div>


<div class='top_menu_link_container2' style="width:auto;"><div class='top_menu_link' style="font-weight:normal; ">
	<a href='{$url->url_create('profile', $user->user_info.user_username)}' class='top_menu_item'><div style="height:29px; color:#fff; cursor:pointer;">{"`$user->user_displayname`"}</div></a>
</div></div>

{*-------------------------------------------*}
	</td>
{*-------------------------------------------*}

{*-----------------------------------------------------------------------------------------------------------*}
{/if}

	<td style="background-image:url(./images/top_right.gif); height:29.5px;" width="10px;">	
</tr>
</table>
</div>
{* END TOP BAR *}


{* HIDDEN POPUP BOX IF USER HAS NEW UPDATES *}
{if $notifys[1] != 0}
  <div style='display: none;' id='newupdates_popup'>
    <div style='margin-top: 10px;'>
      {assign var="notifyscount" value=$notifys[0]|@count}
      {lang_sprintf id=1199 1="<span id='notifyscount'>`$notifyscount`</span>"}
    </div>
    {section name=notify_loop loop=$notifys[0]}
      <div style='font-weight: bold; padding-top: 5px;' id='notify_{$notifys[0][notify_loop].notifytype_id}_{$notifys[0][notify_loop].notify_grouped}'>
        <a href='javascript:void(0);' onClick="parent.deleteNotify('{$notifys[0][notify_loop].notifytype_id}', '{$notifys[0][notify_loop].notify_grouped}');">X</a> <img src='./images/icons/{$notifys[0][notify_loop].notify_icon}' border='0' style='border: none; margin: 0px 5px 0px 5px; display: inline; vertical-align: middle;' class='icon'><a href="{$notifys[0][notify_loop].notify_url}">{lang_sprintf id=$notifys[0][notify_loop].notify_desc 1=$notifys[0][notify_loop].notify_total 2=$notifys[0][notify_loop].notify_text[0]}</a></div>
    {/section}
    </div>
  </div>
{/if}

{literal}
<script type='text/javascript'>
<!--

var se_show_newupdates = new Hash.Cookie('se_show_newupdates', {duration: 3600});

{/literal}{if $notifys[1] != 0}{literal}
  window.addEvent('domready', function() {
    if(se_show_newupdates.get('total') < {/literal}{$notifys[1]}{literal}) {
      se_show_newupdates.set('total', '0');
      $('newupdates').style.display='block';
    }
  });

{/literal}{/if}{literal}
var notify_count = {/literal}{$notifys[1]}{literal};
function deleteNotify(notifytype_id, notify_grouped) {
  $('ajaxframe').src = 'misc_js.php?task=notify_delete&notifytype_id='+notifytype_id+'&notify_grouped='+notify_grouped;
}
function deleteNotifyConfirm(notifytype_id, notify_grouped) {
  $("TB_window").getElements('div[id=notify_'+notifytype_id+'_'+notify_grouped+']').each(function(el) { if(el.id == 'notify_'+notifytype_id+'_'+notify_grouped) { el.style.display = 'none'; notify_count--; }});
  $('newupdates_popup').getElements('div[id=notify_'+notifytype_id+'_'+notify_grouped+']').each(function(el) { if(el.id == 'notify_'+notifytype_id+'_'+notify_grouped) { el.style.display = 'none'; }});
  $('notify_total').innerHTML = notify_count;
  $("TB_window").getElements('span[id=notifyscount]').each(function(el) { if(el.id == 'notifyscount') { el.innerHTML = notify_count; }});
  if(notify_count == 0) {
    TB_remove();
    $('newupdates').style.display = 'none';
  }
}
function hideNewupdates() {
  $('newupdates').fade('out');
  se_show_newupdates.set('total', '{/literal}{$notifys[1]}{literal}');
}
function SwapOut(id1) {
  $(id1).src = Rollarrow1.src;
  return true;
}
function SwapBack(id1) {
  $(id1).src = Rollarrow0.src;
  return true;
}
//-->
</script>
{/literal}

  </td>
  </tr>
  </table>

      {if $notifys[1] != 0}
        <div id='newupdates' style='display:none; height:-5px; line-height:-5px;'></div>
      {/if}</td>


<table cellpadding='0' cellspacing='0' align='center' style='width: 100%;'>
<tr>
<td valign='top'>

{* START MAIN LAYOUT *}
<div class='content'>

  {* SHOW BELOW-MENU ADVERTISEMENT BANNER *}
  {if $ads->ad_belowmenu != ""}<div class='ad_belowmenu' style='display: block; visibility: visible;'>{$ads->ad_belowmenu}</div>{/if}