
{if $user->level_info.level_kiss_allow} 
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

{* POPUP CODE FOR MEMBER kiss *}
{literal}<script type='text/javascript'>
<!--
var open_id = 'kiss';
function se_kiss(id1) {
  if(open_id != '') { se_kiss_close(open_id); }
  document.body.appendChild(document.getElementById(id1));
  document.getElementById(id1).style.display = 'block';
  open_id = id1;
}
function se_kiss_close(id1) {
  document.getElementById(id1).style.display = 'none';
}
//-->
</script>{/literal}
{* END POPUP CODE FOR MEMBER kiss *}

{* kiss TABLE *}

{* SHOW kiss LINK *}

	 {if $owner->user_info.user_username != $user->user_info.user_username}
	 {if $user_kiss_privacy == 1 && $is_friend_kiss == "TRUE" OR $user_kiss_privacy == 0}
	<tr><td class='profile_menu1' nowrap='nowrap'>
	<div id='sendkiss_{$owner->user_info.user_id}'{if $kiss_pending ==1} style='display: none;'{/if}><a href="javascript:TB_show('{lang_print id=90000054}', 'user_kiss_kiss.php?user={$owner->user_info.user_username}&TB_iframe=true&height=200&width=500', '', './images/trans.gif');"><img src='./images/icons/kiss16.gif' class='icon' border='0'>{lang_print id=90000054}</a></div>
	<div id='pendingfriend_{$owner->user_info.user_id}'{if $kiss_pending !=1} style='display: none;'{/if} class='nolink'><img src='./images/icons/kiss16.gif' class='icon' border='0'>{lang_print id=90000053}</div>
      </td></tr>
	{/if}
	{/if}
	{/if}
	


