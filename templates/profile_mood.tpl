{* ModMySocialEngine - MEMBER MOODS *} 

<table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 10px;'> 
<tr><td class='header'>{lang_print id=14000010}</td></tr> 
<tr> 
<td class='profile'td id='profile_stats'> 
<table cellpadding='0' cellspacing='0'> 
<tr> 
<td><strong>{lang_print id=14000011}</strong> {$current_mood} {if $current_mood !="(none)"}<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="32" height="32"> <param name="movie" value="./images/moods/{$mood_image}.swf"> <param name="quality" value="high"> <embed src="./images/moods/{$mood_image}.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="42" height="42"></embed></object>{/if}</td> 
</tr> 
</table> 
</td> 
</tr> 
</table> 
{* END ModMySocialEngine - MEMBER MOODS *} 