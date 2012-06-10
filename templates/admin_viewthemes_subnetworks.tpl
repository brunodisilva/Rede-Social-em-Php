{include file='admin_header.tpl'}

<h2>{lang_print id=11120701}</h2>
{lang_print id=11120702}


<br><br>

{if $is_error != 0}
  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ''}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=$result}</div>
{/if}



<br>

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

<form action='admin_viewthemes_subnetworks.php' method='post' name='items'>
<input type='hidden' name='task' value='update' />
<table cellpadding='0' cellspacing='0' class='list'>
<tr>
<td class='header' width='10'><input type='checkbox' name='select_all' onClick='javascript:doCheckAll()'></td>
<td class='header' width='10'>{lang_print id=11120704}</td>
<td class='header'>{lang_print id=11120705}</td>
<td class='header'>{lang_print id=11120706}</td>
</tr>
  <tr class='{cycle values="background2,background1"}'>
  <td class='item''>{lang_print id=11120707}</td>
  <td class='item'>0</td>
  <td class='item'>{lang_print id=11120708}</td>
  <td class='item'>{lang_print id=11120709}</td>
  </tr>
{section name=subnet_loop loop=$subnets}
  <tr class='{cycle values="background2,background1"}'>
  <td class='item' style='padding-right: 0px;'><input type='checkbox' name='subnetwork_ids[{$subnets[subnet_loop].subnet_id}]' value='1'></td>
  <td class='item'>{$subnets[subnet_loop].subnet_id}</td>
  <td class='item'>{lang_print id=$subnets[subnet_loop].subnet_name}</td>
  <td class='item'>{$subnets[subnet_loop].subnet_theme_name}</td>
  </tr>
{/section}
</table>

<br />

<div class="box">
{lang_print id=11120710}
<br />
<select name="theme_id" size="1">
{foreach from=$theme_options item=theme_option key=theme_key}
<option value="{$theme_key}">{$theme_option}</option>
{/foreach}
</select>
</div>

<p><input type='submit' class='button' value='{lang_print id=11120711}'></p>
</form>

{include file='admin_footer.tpl'}