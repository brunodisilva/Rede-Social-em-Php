{include file='admin_header.tpl'}

{literal}
<script src="../include/js/semods.js"></script>
<script src="../include/js/semods.controls.multiselect.js"></script>
<script type="text/javascript" src="../include/fckeditor/fckeditor.js"></script>

<script>
function init_multiselects() {
  new SEMods.Controls.MultiSelect( "offer_levels" );
  new SEMods.Controls.MultiSelect( "offer_subnets" );
}

SEMods.B.register_onload( init_multiselects );
</script>

  
<style>

.multiSelect {
  background:#FFFFFF url('../images/apps_dropdown.gif') no-repeat scroll right center;
  border:1px solid #BBBBBB;
  display:inline;
  padding:2px 20px 2px 4px;
  width:200px;
  
  Xoverflow: hidden;
}
.multiSelect.hover {
  background:transparent url('../images/apps_dropdown_hover.gif') no-repeat scroll right center;
}
.multiSelect.active, .multiSelect.focus {
  border:1px inset #000000;
}
.multiSelect.active {
  background:transparent url('../images/apps_dropdown_active.gif') no-repeat scroll right center;
}
.multiSelectOptions {
  background:#FFFFFF none repeat scroll 0%;
  border:1px solid #B2B2B2;
  margin-top:-1px;
  max-height:150px;
  overflow:auto;
  Xwidth:206px;
  width:198px;
}
.multiSelectOptions label {
  display:block;
  padding:2px 5px;
}
.multiSelectOptions label.checked {
  background:#E6E6E6 none repeat scroll 0%;
}
.multiSelectOptions label.selectAll {
  border-bottom:1px dotted #CCCCCC;
}
.multiSelectOptions label.hover {
  background:#CFCFCF none repeat scroll 0%;
}

iframe.layerfix {
  display:none;/*sorry for IE5*/
  display/**/:block;/*sorry for IE5*/
  position:absolute;/*must have*/
  top:0;/*must have*/
  left:0;/*must have*/
  z-index:1;/*must have*/
  filter:mask();/*must have*/
  Xwidth:3000px;/*must have for any big value*/
  Xheight:3000px/*must have for any big value*/;
}

table.tabs {
	margin-bottom: 12px;
}
td.tab {
	background: #FFFFFF;
	padding-left: 1px;
	border-bottom: 1px solid #CCCCCC;
}
td.tab0 {
	font-size: 1pt;
	padding-left: 7px;
	border-bottom: 1px solid #CCCCCC;
}
td.tab1 {
	border: 1px solid #CCCCCC;
	border-top: 3px solid #AAAAAA;
	border-bottom: none;
	font-weight: bold;
	padding: 6px 8px 6px 8px;
}
td.tab2 {
	background: #F8F8F8;
	border: 1px solid #CCCCCC;
	border-top: 3px solid #CCCCCC;
	font-weight: bold;
	padding: 6px 8px 6px 8px;
}
td.tab3 {
	background: #FFFFFF;
	border-bottom: 1px solid #CCCCCC;
	padding-right: 12px;
	width: 100%;
	text-align: right;
	vertical-align: middle;
}
.tabs A {
  text-decoration: none;
}

.tabs A:hover {
  text-decoration: underline;
}

</style>
{/literal}


<h2>{lang_print id=100016262}</h2>
{lang_print id=100016263}

<br><br>
<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab1' NOWRAP>{if $item_id !=0}<a href='admin_userpoints_offers.php?task=edit&item_id={$item_id}'>{lang_print id=100016289}{else}{lang_print id=100016289}{/if}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP>{if $item_id !=0}<a href='admin_userpoints_offerphoto.php?item_id={$item_id}'>{lang_print id=100016290}</a>{else}{lang_print id=100016290}{/if}</td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP>{if $item_id !=0}<a href='admin_userpoints_offercomments.php?item_id={$item_id}'>{lang_print id=100016291}</a>{else}{lang_print id=100016291}{/if}</td>
<td class='tab3'>&nbsp;<a href='admin_userpoints_offers.php'>&#171; &nbsp; {lang_print id=100016292}</a></td>
</tr>
</table>

<br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {lang_print id=$error_message}</div>
{elseif $result == 1}
<div class='success'><img src='../images/success.gif' border='0' class='icon'> {lang_print id=100016283}</div>
{/if}

<table cellpadding='0' cellspacing='0'>
<form action='admin_userpoints_offers_generic.php' method='POST'>
<tr>
<td class='form1'>{lang_print id=100016264}</td>
<td class='form2'><input style="width:500px" type='text' class='text' name='offer_title' value='{$offer_title}' size='40' maxlength='250'></td>
</tr>
<tr>
<td class='form1'>{lang_print id=100016265}</td>
<td class='form2'>

  <script type="text/javascript">
  <!--

  var sBasePath = "../include/fckeditor/" ;
  var sToolbar = "userpoints_admin";
  var oFCKeditor = new FCKeditor( 'offer_desc' ) ;
  oFCKeditor.Config["CustomConfigurationsPath"] = "../../js/userpoints_fckconfig.js"  ;
  oFCKeditor.BasePath	= sBasePath ;
  oFCKeditor.Height = "400" ;
  if ( sToolbar != null )
    oFCKeditor.ToolbarSet = sToolbar ;
  oFCKeditor.Value = '{$offer_desc}' ;
  oFCKeditor.Create() ;
  //-->
  </script>

</td>
</tr>

<tr>
<td class='form1'>{lang_print id=100016269}</td>
<td class='form2'><input type='text' class='text' name='offer_cost' value='{$offer_cost}' size='40' maxlength='50'></td>
</tr>

<tr>
<td class='form1'>{lang_print id=100016266}</td>
<td class='form2'><textarea style="width:500px" name='redirect_url' rows='8' cols='40' class='text'>{$redirect_url}</textarea></td>
</tr>

<tr>
<td class='form1'>{lang_print id=100016267}</td>
<td class='form2'> 
<select class='text' name='appear_in_transactions'>
<option value='1'{if $appear_in_transactions == 1} SELECTED{/if}>{lang_print id=100016281}</option>
<option value='0'{if $appear_in_transactions == 0} SELECTED{/if}>{lang_print id=100016282}</option>
</select>
</td>
</tr>

<tr>
<td class='form1'>{lang_print id=100016268}</td>
<td class='form2'>

<select class='text' name='offer_transact_state'>
<option value='0'{if $offer_transact_state == 0} SELECTED{/if}>{lang_print id=100016279}</option>
<option value='1'{if $offer_transact_state == 1} SELECTED{/if}>{lang_print id=100016280}</option>
</select>

</td>
</tr>


<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>&nbsp;</td>
</tr>





<tr>
<td class='form1'>{lang_print id=100016285}</td>
<td class='form2'>


<input id="offer_levels_input" type="text" readonly="readonly" class="multiSelect" value="{lang_print id=100016287}" style="cursor: default;" />
<input type="hidden" id="offer_levels_all" name="offer_levels_all" value="{$levels_all}">
<iframe border=0 frameBorder=0 class="layerfix" id="offer_levels_iframe"></iframe>
<div id="offer_levels_div" class="multiSelectOptions" style="position: absolute; z-index: 99999; display: none;">
<label class="selectAll"><input type="checkbox" class="selectAll" /> {lang_print id=100016288} </label>
{section name=level_loop loop=$levels}
<label><input type="checkbox" name="offer_levels[]" value="{$levels[level_loop].level_id}" {if $levels[level_loop].level_selected} checked="checked" {/if}/> {$levels[level_loop].level_name} {if $levels[level_loop].level_default} {lang_print id=100016284} {/if} </label>
{/section}
</div>

</td>
</tr>


<tr>
<td class='form1'>{lang_print id=100016286}</td>
<td class='form2'>


<input id="offer_subnets_input" type="text" readonly="readonly" class="multiSelect" value="{lang_print id=100016287}" style="cursor: default;" />
<input type="hidden" id="offer_subnets_all" name="offer_subnets_all" value="{$subnets_all}">
<iframe border=0 frameBorder=0 class="layerfix" id="offer_subnets_iframe" style="display:block"></iframe>
<div id="offer_subnets_div" class="multiSelectOptions" style="position: absolute; z-index: 99999; display: none;">
<label class="selectAll"><input type="checkbox" class="selectAll" /> {lang_print id=100016288} </label>
{section name=subnet_loop loop=$subnets}
<label><input type="checkbox" name="offer_subnets[]" value="{$subnets[subnet_loop].subnet_id}" {if $subnets[subnet_loop].subnet_selected} checked="checked" {/if}/> {lang_print id=$subnets[subnet_loop].subnet_name} </label>
{/section}
</div>

</td>
</tr>



  
<tr>
<td class='form1'>{lang_print id=100016272}</td>
<td class='form2'>

<select class='text' name='offer_enabled'>
<option value='1'{if $offer_enabled == 1} SELECTED{/if}>{lang_print id=100016273}</option>
<option value='0'{if $offer_enabled == 0} SELECTED{/if}>{lang_print id=100016274}</option>
</select>

</td>
</tr>


<tr>
<td class='form1'>{lang_print id=100016278}</td>
<td class='form2'>

<select class='text' name='offer_allow_comments'>
<option value='1'{if $offer_allow_comments == 1} SELECTED{/if}>{lang_print id=100016281}</option>
<option value='0'{if $offer_allow_comments == 0} SELECTED{/if}>{lang_print id=100016282}</option>
</select>

</td>
</tr>


<tr>
<td class='form1'>{lang_print id=100016277}</td>
<td class='form2'><input style="width:500px" type='text' class='text' name='offer_tags' value='{$offer_tags}' size='40' maxlength='50'></td>
</tr>

<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>&nbsp;</td>
</tr>


<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td><input type='submit' class='button' value='{lang_print id=100016270}'>&nbsp;</td>
  <input type='hidden' name='task' value='addoffer'>
  <input type='hidden' name='item_id' value='{$item_id}'>
  </form>
  <form action='admin_userpoints_offers.php' method='POST'>
  <td><input type='submit' class='button' value='{lang_print id=100016271}'></td>
  </tr>
  </form>
  </table>
</td>
</tr>
</table>



{include file='admin_footer.tpl'}