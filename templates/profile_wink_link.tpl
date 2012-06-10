
{if $user->level_info.level_winks_allow} 
                {literal}
        <script type="text/javascript">
        <!-- 
	  function friend_update(status, id) {
	    if(status == 'pending') {
	      if($('addfriend_'+id))
	        $('addfriend_'+id).style.display = 'none';
	      if($('confirmfriend_'+id))
	        $('confirmfriend_'+id).style.display = 'none';
	      if($('pendingfriend_'+id))
	        $('pendingfriend_'+id).style.display = 'block';
	      if($('removefriend_'+id))
	        $('removefriend_'+id).style.display = 'none';
	    } else if(status == 'remove') {
	      if($('addfriend_'+id))
	        $('addfriend_'+id).style.display = 'none';
	      if($('confirmfriend_'+id))
	        $('confirmfriend_'+id).style.display = 'none';
	      if($('pendingfriend_'+id))
	        $('pendingfriend_'+id).style.display = 'none';
	      if($('removefriend_'+id))
	        $('removefriend_'+id).style.display = 'block';
	    } else if(status == 'add') {
	      if($('addfriend_'+id))
	        $('addfriend_'+id).style.display = 'block';
	      if($('confirmfriend_'+id))
	        $('confirmfriend_'+id).style.display = 'none';
	      if($('pendingfriend_'+id))
	        $('pendingfriend_'+id).style.display = 'none';
	      if($('removefriend_'+id))
	        $('removefriend_'+id).style.display = 'none';
	    }
	  }
        //-->
        </script>
        {/literal}

{* POPUP CODE FOR MEMBER WINKS *}
{literal}<script type='text/javascript'>
<!--
var open_id = 'winks';
function se_winks(id1) {
  if(open_id != '') { se_winks_close(open_id); }
  document.body.appendChild(document.getElementById(id1));
  document.getElementById(id1).style.display = 'block';
  open_id = id1;
}
function se_winks_close(id1) {
  document.getElementById(id1).style.display = 'none';
}
//-->
</script>{/literal}
{* END POPUP CODE FOR MEMBER WINKS *}

{* WINKS TABLE *}

{* SHOW WINK LINK *}

	 {if $owner->user_info.user_username != $user->user_info.user_username}
	 {if $user_winks_privacy == 1 && $is_friend_winks == "TRUE" OR $user_winks_privacy == 0}
	<tr><td class='profile_menu1' nowrap='nowrap'>
	<div id='sendwink_{$owner->user_info.user_id}'{if $wink_pending ==1} style='display: none;'{/if}><a href="javascript:TB_show('{lang_print id=14000054}', 'user_winks_wink.php?user={$owner->user_info.user_username}&TB_iframe=true&height=200&width=500', '', './images/trans.gif');"><img src='./images/icons/winks16.gif' class='icon' border='0'>{lang_print id=14000054}</a></div>
	<div id='pendingfriend_{$owner->user_info.user_id}'{if $wink_pending !=1} style='display: none;'{/if} class='nolink'><img src='./images/icons/winks16.gif' class='icon' border='0'>{lang_print id=14000053}</div>
      </td></tr>
	{/if}
	{/if}
	{/if}
	


