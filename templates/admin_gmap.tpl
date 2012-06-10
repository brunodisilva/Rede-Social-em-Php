{include file='admin_header.tpl'}

<h2>{lang_print id=11080201}</h2>
{lang_print id=11080202}

<br><br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != 0}
  <div class='success'><img src='../images/success.gif' border='0' class='icon'> {lang_print id=$result}</div>
{/if}


<form action='admin_gmap.php' method='POST' name='info'>

<!-- Nulled by TrioxX
<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11080221}</td></tr>
  <tr><td class='setting1'>{lang_print id=11080222}</td></tr>
  <tr><td class='setting2'><input type='text' name='setting_gmap_license' value='{$setting_gmap_license}' size='30' maxlength="200" /> {lang_print id=11080223}</td>
  </tr>
</table>

<br>
// -->

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11080209}</td></tr>
  <tr><td class='setting1'>{lang_print id=11080210}</td></tr>
  <tr><td class='setting2'><input type='text' name='setting_gmap_api' value='{$setting_gmap_api}' size='80' /></td>
  </tr>
</table>

<br>

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11080227}</td></tr>
  <tr><td class='setting1'>{lang_print id=11080228}</td></tr>
  <tr><td class='setting2'><input type='text' name='setting_gmap_icon' value='{$setting_gmap_icon}' size='80' /></td>
  </tr>
</table>

<br>

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11080205}</td></tr>
  <tr><td class='setting1'>{lang_print id=11080206}</td></tr>
  <tr>
    <td class='setting2'>
    
  <table cellpadding='2' cellspacing='0'>
  <tr>
  <td><input type='radio' name='setting_permission_gmap' id='permission_gmap_1' value='1'{if $setting_permission_gmap == 1} CHECKED{/if}></td>
  <td><label for='permission_gmap_1'>{lang_print id=11080207}</label></td>
  </tr>
  <tr>
  <td><input type='radio' name='setting_permission_gmap' id='permission_gmap_0' value='0'{if $setting_permission_gmap == 0} CHECKED{/if}></td>
  <td><label for='permission_gmap_0'>{lang_print id=11080208}</label></td>
  </tr>
  </table>
    
    </td>
  </tr>
</table>

<br>

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11080213}</td></tr>
  <tr><td class='setting1'>{lang_print id=11080214}</td></tr>
  <tr><td class='setting2'>
  <ul>
  {foreach from=$cats item=cat}
      {assign var=cat_id value=$cat.cat_id}
      <li>{lang_print id=$cat.cat_title}
      <ul>
        <li>{lang_print id=11080215}<br/>
          <select name="mapfields[{$cat.cat_id}][address]">
            <option value="0">SKIP THIS FIELD</option>
          {foreach from=$cat.subcats item=subcat}
            <optgroup label="{lang_print id=$subcat.subcat_title}">
              {foreach from=$subcat.fields item=field}
                <option value="{$field.field_id}" {if $field.field_id == $mapfields.$cat_id.address}selected='selected'{/if}>{lang_print id=$field.field_title}</option>
              {/foreach}
            </optgroup>
          {/foreach}
          </select>
        </li>
        <li>{lang_print id=11080216}<br/>
          <select name="mapfields[{$cat.cat_id}][city]">
            <option value="0">SKIP THIS FIELD</option>
          {foreach from=$cat.subcats item=subcat}
            <optgroup label="{lang_print id=$subcat.subcat_title}">
              {foreach from=$subcat.fields item=field}
                <option value="{$field.field_id}" {if $field.field_id == $mapfields.$cat_id.city}selected='selected'{/if}>{lang_print id=$field.field_title}</option>
              {/foreach}
            </optgroup>
          {/foreach}
          </select>
        </li>
        <li>{lang_print id=11080217}<br/>
          <select name="mapfields[{$cat.cat_id}][region]">
            <option value="0">SKIP THIS FIELD</option>
          {foreach from=$cat.subcats item=subcat}
            <optgroup label="{lang_print id=$subcat.subcat_title}">
              {foreach from=$subcat.fields item=field}
                <option value="{$field.field_id}" {if $field.field_id == $mapfields.$cat_id.region}selected='selected'{/if}>{lang_print id=$field.field_title}</option>
              {/foreach}
            </optgroup>
          {/foreach}
          </select>
        </li>
        <li>{lang_print id=11080218}<br/>
          <select name="mapfields[{$cat.cat_id}][country]">
            <option value="0">SKIP THIS FIELD</option>
          {foreach from=$cat.subcats item=subcat}
            <optgroup label="{lang_print id=$subcat.subcat_title}">
              {foreach from=$subcat.fields item=field}
                <option value="{$field.field_id}" {if $field.field_id == $mapfields.$cat_id.country}selected='selected'{/if}>{lang_print id=$field.field_title}</option>
              {/foreach}
            </optgroup>
          {/foreach}
          </select>
        </li>
      </ul>
    </li>
  {/foreach}
  </ul>
  </td></tr>

  <tr><td class='setting1'>{lang_print id=11080224}</td></tr>
  <tr>
    <td class='setting2'>
    
  <table cellpadding='2' cellspacing='0'>
  <tr>
  <td><input type='radio' name='setting_gmap_profile_embed' id='gmap_profile_embed_1' value='1'{if $setting_gmap_profile_embed == 1} CHECKED{/if}></td>
  <td><label for='gmap_profile_embed_1'>{lang_print id=11080225}</label></td>
  </tr>
  <tr>
  <td><input type='radio' name='setting_gmap_profile_embed' id='gmap_profile_embed_0' value='0'{if $setting_gmap_profile_embed == 0} CHECKED{/if}></td>
  <td><label for='gmap_profile_embed_0'>{lang_print id=11080226}</label></td>
  </tr>
  </table>
    
    </td>
  </tr>  
   
</table>

<br>




<input type='submit' class='button' value='{lang_print id=11080204}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}