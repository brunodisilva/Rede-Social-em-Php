{include file='admin_header.tpl'}

{literal}
<script>
function enable_offer(offer_id, enable) {
  var asyncform = document.getElementById('asyncform');
  document.getElementById('asyncform_task').value = "enable";
  document.getElementById('asyncform_offer_id').value = offer_id;
  document.getElementById('asyncform_enable').value = enable;
  
  asyncform.submit();
}

function delete_offer(offer_id) {
  var asyncform = document.getElementById('asyncform');
  document.getElementById('asyncform_task').value = "delete";
  document.getElementById('asyncform_offer_id').value = offer_id;
  
  asyncform.submit();
}

function show_upselector() {
  document.getElementById("upselector_button").style['display'] = "none";
  document.getElementById("upselector_div").style['display'] = "block";
}

</script>
{/literal}

<h2>{lang_print id=100016198}</h2>
{lang_print id=100016199}

<br><br>

<table cellpadding='0' cellspacing='0' align='center'>
<tr>
<td align='center'>
<div class='box'>
<table cellpadding='0' cellspacing='0' align='center'>
<tr><form action='admin_userpoints_shop.php' method='POST'>
<td>{lang_print id=100016200}<br><input type='text' class='text' name='f_title' value='{$f_title}' size='15' maxlength='50'>&nbsp;</td>
<td>{lang_print id=100016221}<br><select class='text' name='f_level'><option></option>{section name=level_loop loop=$levels}<option value='{$levels[level_loop].level_id}'{if $f_level == $levels[level_loop].level_id} SELECTED{/if}>{$levels[level_loop].level_name}</option>{/section}</select>&nbsp;</td>
<td>{lang_print id=100016222}<br><select class='text' name='f_subnet'><option></option>{section name=subnet_loop loop=$subnets}<option value='{$subnets[subnet_loop].subnet_id}'{if $f_subnet == $subnets[subnet_loop].subnet_id} SELECTED{/if}>{lang_print id=$subnets[subnet_loop].subnet_name}</option>{/section}</select>&nbsp;</td>
<td>{lang_print id=100016225}<br><select class='text' name='f_enabled'><option></option><option value='1'{if $f_enabled == "1"} SELECTED{/if}>{lang_print id=100016206}</option><option value='0'{if $f_enabled == "0"} SELECTED{/if}>{lang_print id=100016207}</option></select>&nbsp;&nbsp;&nbsp;</td>
<td valign='bottom'><input type='submit' class='button' value='{lang_print id=100016211}'></td>
<input type='hidden' name='s' value='{$s}'>
</form>
</tr>
</table>
</div>
</td></tr></table>
  
<br>

{if $total_offers == 0}

  <table cellpadding='0' cellspacing='0' width='400' align='center'>
  <tr>
  <td align='center'>
  <div class='box'><b>{lang_print id=100016218}</b></div>
  </td></tr></table>
  <br>

  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr>
  <td>
    <br>
    <input id="upselector_button" type='button' class='button' value='{lang_print id=100016220}' onclick="show_upselector()">

    <div id="upselector_div" style="display:none">
      <form action='admin_userpoints_shop.php' method='post' name='items'>
        {lang_print id=100016229} &nbsp;
        <select class='text' name='offer_type'><option></option>
        {section name=offertype_loop loop=$offer_types}<option value='{$offer_types[offertype_loop].offertype_id}'>{$offer_types[offertype_loop].offertype_title}</option>{/section}</select>&nbsp;
      <input type='submit' class='button' value='{lang_print id=100016832}'>
      <input type='hidden' name='task' value='addnew'>
      </form>
    </div>
    
  </td>
  </tr>
  </table>

{else}

  {* JAVASCRIPT FOR CHECK ALL *}
  {literal}
  <script language='JavaScript'> 
  <!---
  var checkboxcount = 1;
  function doCheckAll() {
    if(checkboxcount == 0) {
      with (document.items) {
      for (var i=0; i < elements.length; i++) {
      if (elements[i].type == 'checkbox') {
      elements[i].checked = false;
      }}
      checkboxcount = checkboxcount + 1;
      }
    } else
      with (document.items) {
      for (var i=0; i < elements.length; i++) {
      if (elements[i].type == 'checkbox') {
      elements[i].checked = true;
      }}
      checkboxcount = checkboxcount - 1;
      }
  }
  // -->
  </script>
  {/literal}
  
  <div class='pages'>{$total_offers} {lang_print id=100016213} &nbsp;|&nbsp; {lang_print id=100016214} {section name=page_loop loop=$pages}{if $pages[page_loop].link == '1'}{$pages[page_loop].page}{else}<a href='admin_userpoints_shop.php?s={$s}&p={$pages[page_loop].page}&f_title={$f_title}&f_email={$f_email}&f_level={$f_level}&f_enabled={$f_enabled}'>{$pages[page_loop].page}</a>{/if} {/section}</div>
  
  <table cellpadding='0' cellspacing='0' class='list' width='100%'>
  <tr>
  <!--<td class='header' width='10'><input type='checkbox' name='select_all' onClick='javascript:doCheckAll()'></td>-->
  <td class='header'><a class='header' href='admin_userpoints_shop.php?s={$at}&p={$p}&f_title={$f_title}&f_level={$f_level}&f_subnet={$f_subnet}&f_enabled={$f_enabled}'>{lang_print id=100016200}</a></td>

  <td class='header'>{lang_print id=100016201}</td>
  
  <td class='header'>{lang_print id=100016227}</td>
  
<!--
  <td class='header'>{lang_print id=100016202}</td>
  <td class='header'>{lang_print id=100016203}</td>
-->

  <td class='header'>{lang_print id=100016228}</td>
  <td class='header'>{lang_print id=100016204}</td>
  <td class='header'>{lang_print id=100016205}</td>
  <td class='header'>{lang_print id=100016225}</td>
  <td class='header'>{lang_print id=100016210}</td>
  <td class='header'>{lang_print id=100016226}</td>
  
  </tr>
  
  <!-- LOOP THROUGH OFFERS -->
  {section name=offer_loop loop=$offers}
    <tr class='{cycle values="background1,background2"}'>
    <td class='item'>{$offers[offer_loop].offer_title}</td>
    <td class='item'>{$offers[offer_loop].offer_type}</td>
    <td class='item'>{$offers[offer_loop].offer_cost}</td>
<!--
    <td class='item'>{$offers[offer_loop].offer_levels}</td>
    <td class='item'>{$offers[offer_loop].offer_subnets}</td>
-->
    <td class='item'>{$offers[offer_loop].offer_engagements}</td>
    <td class='item'>{$offers[offer_loop].offer_views}</td>
    <td class='item'>{$offers[offer_loop].total_comments}</td>
    <td class='item'>{if $offers[offer_loop].offer_enabled}{lang_print id=1000}{else}{lang_print id=1001}{/if}</td>


    <td class='item' nowrap='nowrap'>{$datetime->cdate($setting.setting_dateformat, $datetime->timezone($offers[offer_loop].offer_date, $setting.setting_timezone))}</td>

    <td class='item' nowrap='nowrap'><a href='admin_userpoints_shop.php?task=edit&item_id={$offers[offer_loop].offer_id}'>{lang_print id=100016208}</a> | <a href='javascript:enable_offer({$offers[offer_loop].offer_id}, {$offers[offer_loop].offer_enabledisable})'>{if $offers[offer_loop].offer_enabledisable}{lang_print id=100016219}{else}{lang_print id=100016209}{/if}</a> | <a href='javascript:delete_offer({$offers[offer_loop].offer_id})'>{lang_print id=100016212}</a></td>
    </tr>
  {/section}
  </table>

  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr>
  <td>
    <br>
    <input id="upselector_button" type='button' class='button' value='{lang_print id=100016220}' onclick="show_upselector()">

    <div id="upselector_div" style="display:none">
      <form action='admin_userpoints_shop.php' method='post' name='items'>
        {lang_print id=100016229} &nbsp;
        <select class='text' name='offer_type'><option></option>
        {section name=offertype_loop loop=$offer_types}<option value='{$offer_types[offertype_loop].offertype_id}'>{$offer_types[offertype_loop].offertype_title}</option>{/section}</select>&nbsp;
      <input type='submit' class='button' value='{lang_print id=100016833}'>
      <input type='hidden' name='task' value='addnew'>
      </form>
    </div>
    
  </td>
  <td align='right' valign='top'>
    <div class='pages2'>{$total_offers} {lang_print id=100016213} &nbsp;|&nbsp; {lang_print id=100016214} {section name=page_loop loop=$pages}{if $pages[page_loop].link == '1'}{$pages[page_loop].page}{else}<a href='admin_userpoints_shop.php?s={$s}&p={$pages[page_loop].page}&f_title={$f_title}&f_level={$f_level}&f_enabled={$f_enabled}'>{$pages[page_loop].page}</a>{/if} {/section}</div>
  </td>
  </tr>
  </table>

  <form method=POST id="asyncform" action="admin_userpoints_shop.php">
    <input type="hidden" id="asyncform_task" name="task">
    <input type="hidden" id="asyncform_offer_id" name="item_id">
    <input type="hidden" id="asyncform_enable" name="enable">
  </form>

{/if}

{include file='admin_footer.tpl'}