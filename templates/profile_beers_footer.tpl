{* MEMBER beerS POPUP *}
 <table id='beers' cellpadding='0' cellspacing='0' class='profile_event_popup2'>
  <tr><td class='profile_event_transparent' colspan='3' style='height: 20px;'>&nbsp;</td></tr>
  <tr>
  <td class='profile_event_transparent' style='width: 20px;'>&nbsp;</td>
  <td class='profile_event_popup2'>
    <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
    <td class='profile_event_popup_title'><font color="#000000">{lang_print id=16000055} {$owner->user_info.user_username}?</font></td>
    </tr>
    </table>
          <div class='profile_event_spacer'></div>
      <table cellpadding='0' cellspacing='0' width='100%'>
      <tr>
        <td class='photo'valign='top' width='20' style='padding-right: 10px;'><img class='photo' src='{$owner->user_photo("./images/nophoto.gif")}' class='photo' width='{$misc->photo_size($owner->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></td>
      <td valign='top'><br>
        <div><font color="#000000">{lang_print id=16000056} {$owner->user_info.user_username}. {lang_print id=16000057}</font></div>
        <br><br> 
	<form action='user_beers_beer.php' method='POST' name='addform'>
	<div style='padding-top: 5px;' align='center'>
<input type='submit' class='button' value='{lang_print id=16000058}'>
       &nbsp;
     <input type='hidden' name='task' value='beer'>
     <input type='hidden' name='user' value='{$owner->user_info.user_username}'>
     </form>
	 <br><br>
	   <form action='user_beers_beer.php' method='POST'>
       <input type='submit' class='button' value='{lang_print id=16000059}'>
       <input type='hidden' name='task' value='cancel'>
       <input type='hidden' name='user' value='{$owner->user_info.user_username}'>
       </form>		  
	</div>
	</div>
	  
			  </td>
      <td valign='top' align='right' nowrap='nowrap'>
      </tr>


	  </table>
  </td>
 <td class='profile_event_transparent' style='width: 20px;'>&nbsp;</td>
  </tr>
  <tr><td colspan='3' class='profile_event_transparent' style='height: 20px;'>&nbsp;</td></tr>
  </table>
{* END MEMBER beerS POPUP *}
