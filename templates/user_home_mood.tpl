{literal}
<script language="javascript">
<!--

var state = 'none';

function showhide(layer_ref) {

if (state == 'block') {
state = 'none';
}
else {
state = 'block';
}
if (document.all) {
eval( "document.all." + layer_ref + ".style.display = state");
}
if (document.layers) { 
document.layers[layer_ref].display = state;
}
if (document.getElementById &&!document.all) {
hza = document.getElementById(layer_ref);
hza.style.display = state;
}
}
//-->
</script>{/literal}

{* ModMySocialEngine - MEMBER MOODS *} 
<table cellpadding='0' cellspacing='0' width='100%' style='margin-top: 10px;'> 
<tr><td class='header'>{lang_print id=14000010}</td></tr> 
<tr> 
<td class='home_box'> 
<table cellpadding='0' cellspacing='0'> 
<tr> 
<td> 
<form action='user_mood.php' method='POST'> 
<table cellpadding='0' cellspacing='0' > 
<tr> 

<td colspan="2"><b>{lang_print id=14000011}</b> {$current_mood} {if $current_mood != "(none)"}<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="32" height="32"> <param name="movie" value="./images/moods/{$mood_image}.swf"> <param name="quality" value="high"> <embed src="./images/moods/{$mood_image}.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="42" height="42"></embed></object>{/if}</td><br> 
</tr> 
<tr> 
<td><a href="javascript:void(0);" onClick="showhide('mood');"> 
<div align="center">{lang_print id=14000012}</div></td> 
<td> </td> 
</tr> 
<tr> 
<td colspan="4"> 
<div class='faq' style='display: none;' id='mood'> 
<table border="0" align="center"> 
<tr> 
<td>

 <select name='mood_new' value="{$user->user_info.user_mood}">
	{section name=mood_options loop=$moods}
    	<option >{$moods[mood_options].mood_name}</option>
	{/section}
    </select>
</td> 
</tr> 
<tr> 
<td><br><center><input type='submit' class='button' value='{lang_print id=14000013}'></td></center> 
</tr> 
</table></div> 
<div align="center"></div></td> 
</tr> 
</table> 
<input type='hidden' name='task' value='dosave'> 
<input type='hidden' name='return_url' value='{$return_url}'> 
</form> 
</td> 
</tr> 
</table> </td> 
</tr> 
</table> 
{* END ModMySocialEngine - MEMBER MOODS *} 
