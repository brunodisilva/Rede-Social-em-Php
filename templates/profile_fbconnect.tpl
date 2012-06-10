{*  $Id: profile_fbconnect.tpl 1 2009-07-04 09:36:11Z SocialEngineAddOns $ *}

{* PROFILE TAB *}

{if !empty($error_message_facebook_connect)}
  <div class='error'><img src='./images/error.gif' border='0' class='icon'> {lang_print id=$error_message_facebook_connect}</div>
{/if}

{if $fbprofile_view_status == 0}{* User is not the profile's owner, and the owner has not associated with FB Connect *}
{lang_print id=650000065}<br />
    
{elseif $fbprofile_view_status == 1}

	{* SHOW PROFILE CATS AND FIELDS *}
  <div class='profile_headline{if !$smarty.section.subcat_loop.first}2{/if}'><b>{lang_print id=$cats[cat_loop].subcats[subcat_loop].subcat_title}</b></div>
	<div>
		  <div style='float: left; width: 50%;'>
	    	<div class='profile_headline'>
	        	{$owner->user_displayname_short}'s {lang_print id=650000011}
	      </div>
		  </div>
		  <div style='clear: both;'></div>
		</div>
  <table cellpadding='0' cellspacing='0'>
  	{* LOOP THROUGH FIELDS IN TAB, ONLY SHOW FIELDS THAT HAVE BEEN FILLED IN *}
    {foreach from=$user_fb_profile key=k item=v}
    <tr>
    	<td valign='top' style='padding-right: 10px;' nowrap='nowrap'>
      	{$k}:
      </td>  	
      <td valign='top' style='padding-right: 10px;'>
      	{$v}
      </td>    
    </tr>
    {/foreach}
  </table>
  {* END PROFILE TABS AND FIELDS *}

	{* FRIENDS TAB *}
  {if $total_facebook_friends != 0}
		<div>
		  <div style='float: left; width: 100%;margin-top:10px;'>
	    	<div class='profile_headline'>
				 {if !empty($user->user_info.user_id) && ($user->user_info.user_id ==  $owner->user_info.user_id) }
	        	{lang_sprintf id=650000105 1=$owner->user_displayname_short} {$fbconnect_setting.site_name} ({$total_facebook_friends})
	      {else}
	          {lang_sprintf id=650000106 1=$owner->user_displayname_short} {$fbconnect_setting.site_name} ({$total_facebook_friends})
	      {/if}
	      </div>
		  </div>
		  <div style='clear: both;'></div>
		</div>
	
		{* ASSIGN INDICES FRIENDS CAROUSEL*}
		{assign var="current_index_user_fbconnected_friend" value=3}
		{capture assign="previous_index_user_fbconnected_friend"}{if $current_index_user_fbconnected_friend == 0}{math equation="x_user_fbconnected_friend-1" x_user_fbconnected_friend=$user_fbconnected_friends|@count}{else}{math equation="x_user_fbconnected_friend-1" x_user_fbconnected_friend=$current_index_user_fbconnected_friend}{/if}{/capture}
		{capture assign="next_index_user_fbconnected_friend"}{if $current_index_user_fbconnected_friend+1 == $user_fbconnected_friends|@count}0{else}{math equation="x_user_fbconnected_friend+1" x_user_fbconnected_friend=$current_index_user_fbconnected_friend}{/if}{/capture}
		{capture assign="current_num_user_fbconnected_friend"}{math equation="x_user_fbconnected_friend+1" x_user_fbconnected_friend=$current_index_user_fbconnected_friend}{/capture}
	  
		<table cellpadding='0' cellspacing='5' align='center' style='margin-top: 20px;border:1px solid #cccccc;'>
			<tr>
			<td><a href='javascript:void(0);' onClick='moveLeft_user_fbconnected_friend();this.blur()'><img src='./images/icons/fbconnect_friend_left.png' border='0' onMouseOver="this.src='./images/icons/fbconnect_friend_left_over.png';" onMouseOut="this.src='./images/icons/fbconnect_friend_left.png';" ></a></td>
			<td>			
			  <div id='user_fbconnected_friend_carousel_user_fbconnected_friend' style='width: 598px; margin: 0px 5px 0px 5px; text-align: center; overflow: hidden;'>
			    <table cellpadding='0' cellspacing='0'>
			    <tr>
			    <td id='thumb_user_fbconnected_friend-2' style='padding: 0px 5px 0px 5px;'><img src='./images/media_placeholder.gif' border='0' width='70'></td>
			    <td id='thumb_user_fbconnected_friend-1' style='padding: 0px 5px 0px 5px;'><img src='./images/media_placeholder.gif' border='0' width='70'></td>
			    <td id='thumb_user_fbconnected_friend0' style='padding: 0px 5px 0px 5px;'><img src='./images/media_placeholder.gif' border='0' width='70'></td>
						{section name=friend_loop loop=$user_fbconnected_friends}
					    <td id='thumb_user_fbconnected_friend{$smarty.section.friend_loop.iteration}' class='carousel_item'>					
							  <a href='{$url->url_create('profile',$user_fbconnected_friends[friend_loop]->user_info.user_username)}'>
								<img src='{$user_fbconnected_friends[friend_loop]->user_photo('./images/nophoto.gif')}' border='0' width="100" alt="{lang_sprintf id=509 1=$user_fbconnected_friends[friend_loop]->user_displayname_short}">
					</a><br />
					 		 <a href='{$url->url_create('profile',$user_fbconnected_friends[friend_loop]->user_info.user_username)}'>
{$user_fbconnected_friends[friend_loop]->user_displayname}
</a>
			    </td>
				  {/section}
  		    <td id='thumb_user_fbconnected_friend{math equation="x_user_fbconnected_friend+1" x_user_fbconnected_friend=$user_fbconnected_friends|@count}' style='padding: 0px 5px 0px 5px;'><img src='./images/media_placeholder.gif' border='0' width='70'></td>
			    <td id='thumb_user_fbconnected_friend{math equation="x_user_fbconnected_friend+2" x_user_fbconnected_friend=$user_fbconnected_friends|@count}' style='padding: 0px 5px 0px 5px;'><img src='./images/media_placeholder.gif' border='0' width='70'></td>
			    <td id='thumb_user_fbconnected_friend{math equation="x_user_fbconnected_friend+3" x_user_fbconnected_friend=$user_fbconnected_friends|@count}' style='padding: 0px 5px 0px 5px;'><img src='./images/media_placeholder.gif' border='0' width='70'></td>
			    </tr>
			</table>
	  </div>
			</td>
			<td><a href='javascript:void(0);' onClick='moveRight_user_fbconnected_friend();this.blur()'><img src='./images/icons/fbconnect_friend_right.png' border='0' onMouseOver="this.src='./images/icons/fbconnect_friend_right_over.png';" onMouseOut="this.src='./images/icons/fbconnect_friend_right.png';"></a></td>
			</tr>
	  </table>
	  
		{* JAVASCRIPT FOR FRIENDS CAROUSEL *}
		{literal}
		<script type='text/javascript'>
		<!--
		
		  var visiblePhotos_user_fbconnected_friend = 7;
		  var current_id_user_fbconnected_friend = 0;
		  var myFx_user_fbconnected_friend;
		
		  window.addEvent('domready', function() {
		    myFx_user_fbconnected_friend = new Fx.Scroll('user_fbconnected_friend_carousel_user_fbconnected_friend');
		    current_id_user_fbconnected_friend = parseInt({/literal}{math equation="x_user_fbconnected_friend-2" x_user_fbconnected_friend=$current_index_user_fbconnected_friend}{literal});
		    var position_user_fbconnected_friend = $('thumb_user_fbconnected_friend'+current_id_user_fbconnected_friend).getPosition($('user_fbconnected_friend_carousel_user_fbconnected_friend'));
		    myFx_user_fbconnected_friend.set(position_user_fbconnected_friend.x, position_user_fbconnected_friend.y);
		  });
		
		
		  function moveLeft_user_fbconnected_friend() {
		    if($('thumb_user_fbconnected_friend'+(current_id_user_fbconnected_friend-1))) {
		      myFx_user_fbconnected_friend.toElement('thumb_user_fbconnected_friend'+(current_id_user_fbconnected_friend-1));
		      myFx_user_fbconnected_friend.toLeft();
		      current_id_user_fbconnected_friend = parseInt(current_id_user_fbconnected_friend-1);
		    }
		  }
		
		  function moveRight_user_fbconnected_friend() {
		    if($('thumb_user_fbconnected_friend'+(current_id_user_fbconnected_friend+visiblePhotos_user_fbconnected_friend))) {
		      myFx_user_fbconnected_friend.toElement('thumb_user_fbconnected_friend'+(current_id_user_fbconnected_friend+1));
		      myFx_user_fbconnected_friend.toRight();
		      current_id_user_fbconnected_friend = parseInt(current_id_user_fbconnected_friend+1);
		    }
		  }
		
		//-->
		</script>
		{/literal}
	  	
	
	
  {/if}

{elseif $fbprofile_view_status == 2 && empty($error_message_facebook_connect)}{* User is the profile's owner, but he has not associated with FB Connect *}
   	<span style="line-height:22px;">{lang_print id=650000066}<br />
   	{lang_print id=650000067}</span><br /><br />
     {$fb_connect_button}
     
{elseif $fbprofile_view_status == 3 && empty($error_message_facebook_connect)}{* User is the profile's owner, and he is already associated his profile with facebook but currently he is not logged in as a facebook *}
   	<span style="line-height:22px;">
   	<b>{lang_print id=650001027}</b></span><br /><br />
     {$fb_connect_button}
{/if}
   
{* END FRIENDS TAB *}