{include file='admin_header.tpl'}

{*  $Id: admin_fbconnect.tpl 1 2009-07-04 09:36:11Z SocialEngineAddOns $ *}
<h2>{lang_print id=65000001}</h2>
 {lang_print id=65000002} <br>
  <br>
  
  {if !empty($error_message_lsetting)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message_lsetting}</div>
	{/if}
	{if !empty($error_message)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {lang_print id=$error_message}</div>
	{/if}
	{if !empty($error_message_button)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {lang_print id=$ierror_message_button}</div>
	{/if}
	{if !empty($error_message_site_name)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {lang_print id=$error_message_site_name}</div>
	{/if}
	{if !empty($error_message_redirect_url)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {lang_print id=$error_message_redirect_url}</div>
	{/if}
	{if !empty($error_message_invite_message)}
	  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {lang_print id=$error_message_invite_message}</div>
	{/if}
	{if !empty($success_message) }
	  <div class = 'success'><img src = '../images/success.gif' border = '0' class = 'icon'>{lang_print id=$success_message}</div>
	{/if}
  
	<form action='admin_fbconnect.php' method='post'>
  <div id="tipDiv" style="display: none;"></div>
    <iframe id="tabframe" name="ajax_frame" style="display: none;" src="images/a.htm" height="0" width="0"></iframe>
      <table cellpadding="0" cellspacing="0" width="600">
        <tbody>
          <tr>
            <td class="header">
            	{lang_print id=650000068}
						</td>
          </tr>
          <tr>
            <td class="setting1">{lang_print id=650000069} {lang_print id=650000070}.</td>
          </tr>
          <tr>
            <td class="setting2">
							 <input type='text' class='text' name='lsettings' value='{$result.license_key}' size='50' maxlength='100'>
              {lang_print id=650000071}
						</td>
          </tr>
        </tbody>
      </table>
			<br />
			
			
	   <table cellpadding="0" cellspacing="0" width="600">
        <tbody>
          <tr>
            <td class="header">
							{lang_print id=650000072}
						</td>
          </tr>
          <tr>
            <td class="setting1">
							<b>{lang_print id=65000004}</b>
						</td>
          </tr>
          <tr>
            <td class="setting2">
							<input type='text' class='text' name='api_key' value='{$result.api_key}' size='50' maxlength='200'><br />
              {lang_print id=650000016}
						</td>
          </tr>
          <tr>
            <td class="setting1">
							<b>{lang_print id=65000005}</b>
						</td>
          </tr>
          <tr>
            <td class="setting2">
							 <input type='text' class='text' name='secret' value='{$result.secret}' size='50' maxlength='200'><br />
             {lang_print id=650000012}
						</td>
          </tr>
        </tbody>
      </table>
			<br />
			
     <table cellpadding="0" cellspacing="0" width="600">
      <tbody>
        <tr>
          <td class="header">
						{lang_print id=650000074}
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>{lang_print id=65000006}</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						<table cellpadding="0" cellspacing="0">
							<tr>
									<td width="25"><input name="facebook_button" id="facebook_button_image_1" value="large_long" {if $result.facebook_button == 'large_long'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_1"><img src = '../images/icons/Connect_white_large_long.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_2" value="large_short" {if $result.facebook_button == 'large_short'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_2"><img src = '../images/icons/Connect_white_large_short.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_3" value="medium_long" {if $result.facebook_button == 'medium_long'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_3"><img src = '../images/icons/Connect_white_medium_long.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_4" value="medium_short" {if $result.facebook_button == 'medium_short'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_4"><img src = '../images/icons/Connect_white_medium_short.gif' border = '0'></label></td>
								</tr>
								
								<tr>
									<td><input name="facebook_button" id="facebook_button_image_5" value="small_short" {if $result.facebook_button == 'small_short'}checked="checked" {/if} type="radio"></td>
									<td><label for="facebook_button_image_5"><img src = '../images/icons/Connect_white_small_short.gif' border = '0'></label></td>
								</tr>			
							<tr>
								<td colspan="2" style="padding-top:5px;">
									{lang_print id=650000075}
								</td>
							</tr>
						</table>
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>{lang_print id=65000007}</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						<input type='text' class='text' name='site_name' value='{$result.site_name}' size='50' maxlength='100'><br>{lang_print id=650000013}
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>{lang_print id=65000008}</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						<input type='text' class='text' name='redirect_url' value='{$result.redirect_url}' size='50' maxlength='100'><br>{lang_print id=650000014}
					</td>
        </tr>
        <tr>
          <td class="setting1">
						<b>{lang_print id=65000009}</b>
					</td>
        </tr>
        <tr>
          <td class="setting2">
						{if !empty($result.invite_message)}<textarea class="text" name="invite_message" rows="5" cols="50" style="width: 100%;">{$result.invite_message}</textarea>{else}<textarea class="text" name="invite_message" rows="5" cols="50" style="width: 100%;"></textarea>{/if}<br>{lang_print id=650000015}
					</td>
        </tr>
      </tbody>
    </table>
		<br />
		
    <table cellpadding="0" cellspacing="0" width="600">
      <tbody>
        <tr>
          <td class="header">
						{lang_print id=650000076}
					</td>
        </tr>
        <tr>
          <td class="setting2">
						<table cellpadding="2" cellspacing="0">
             	<tbody>
                <tr>
                  <td><input name="signup_setting" id="signup_setting_1" value="1" {if $result.signup_setting == '1'}checked="checked" {/if} type="radio"></td>
									<td><label for="signup_setting_1">{lang_print id=650000050}</label></td>
                </tr>
                <tr>
                	<td><input name="signup_setting" id="signup_setting_2" value="0" {if $result.signup_setting == '0'} checked="checked" {/if} type="radio"></td>
									<td><label for="signup_setting_2">{lang_print id=650000049}</label></td>
                </tr>
              </tbody>
            </table>
					</td>
        </tr>
      </tbody>
    </table>
    <br>
    
    
    <table cellpadding="0" cellspacing="0" width="600">
      <tbody>
        <tr>
          <td class="header">
						{lang_print id=650000107}
					</td>
        </tr>
         <tr>
          <td class="setting1">
						<b>{lang_print id=650001028}</b>
					</td>
        </tr>
        <tr>
          <td class="setting2"> 
          	<table cellpadding="2" cellspacing="0">
             	<tbody>
                <tr>
                  <td><input name="siteimage_setting" id="siteimage_setting_1" value="1" {if $result.siteimage_setting == '1'}checked="checked" {/if} type="radio" onclick="showContent('id_thubnail_text');"></td>
									<td><label for="siteimage_setting_1">{lang_print id=650001029}</label></td>
                </tr>
                <tr>
                	<td><input name="siteimage_setting" id="siteimage_setting_2" value="0" {if $result.siteimage_setting == '0'} checked="checked" {/if} type="radio" onclick="hideContent('id_thubnail_text');"></td>
									<td><label for="siteimage_setting_2">{lang_print id=650001030}</label></td>
                </tr>
                <tr>
				        	<td></td>
									<td><b>{lang_print id=650001031}</b></td>
                </tr>
              </tbody>
            </table>
					</td>
        </tr>
        <tr>
	        <td>
		        <div id="id_thubnail_text" {if $result.siteimage_setting == '0'} style="display:none;"{/if}>
		         <table width="100%" cellpadding="0" cellspacing="0">
					      <tr>
				          <td class="setting1" style="background:#e2eefd;">
										<b>{lang_print id=650000108}</b>
									</td>
				        </tr>
				        <tr>
				          <td class="setting2"> 
				          	<table cellpadding="2" cellspacing="0">
				             	<tbody>
				                <tr>
				                <!--No Facebook Logo on avatar, with width 50px and height 50px-->
				                  <td><input name="thumbnail_setting" id="thumbnail_setting_1" value="0" {if $result.thumbnail_setting == '0'}checked="checked" {/if} type="radio"></td>
													<td><label for="thumbnail_setting_1">{lang_print id=650001010}</label></td>
				                </tr>
				                <tr>
				                 <!--No Facebook Logo on avatar, with width 50px and max height 150px-->
				                	<td><input name="thumbnail_setting" id="thumbnail_setting_2" value="1" {if $result.thumbnail_setting == '1'} checked="checked" {/if} type="radio"></td>
													<td><label for="thumbnail_setting_2">{lang_print id=650001011}</label></td>
				                </tr>
				                 <tr>
				                  <!--No Facebook Logo on avatar, with max width 100px and max height 300px-->
				                  <td><input name="thumbnail_setting" id="thumbnail_setting_3" value="2" {if $result.thumbnail_setting == '2'}checked="checked" {/if} type="radio"></td>
													<td><label for="thumbnail_setting_3">{lang_print id=650001012}</label></td>
				                </tr>
				                 <tr>
				                  <!--Show Facebook logo on avatar, with width 50px and height 50px-->
				                  <td><input name="thumbnail_setting" id="thumbnail_setting_4" value="3" {if $result.thumbnail_setting == '3'}checked="checked" {/if} type="radio"></td>
													<td><label for="thumbnail_setting_4">{lang_print id=650001013}</label></td>
				                </tr>
				                <tr>
				                 <!--Show Facebook logo on avatar, with width 50px and max height 150px-->
				                  <td><input name="thumbnail_setting" id="thumbnail_setting_5" value="4" {if $result.thumbnail_setting == '4'}checked="checked" {/if} type="radio"></td>
													<td><label for="thumbnail_setting_5">{lang_print id=650001014}</label></td>
				                </tr>
				                <tr>
				                 <!--Show Facebook logo on avatar, with max width 100px and max height 300px-->
				                  <td><input name="thumbnail_setting" id="thumbnail_setting_6" value="5" {if $result.thumbnail_setting == '5'}checked="checked" {/if} type="radio"></td>
													<td><label for="thumbnail_setting_6">{lang_print id=650001015}</label></td>
				                </tr>
				              </tbody>
				            </table>
									</td>
				        </tr>
				        <tr>
				          <td class="setting1" style="background:#e2eefd;">
										<b>{lang_print id=650000109} </b>
									</td>
				        </tr>        
				         <tr>
				          <td class="setting2">
				        
										<table cellpadding="2" cellspacing="0">
				             	<tbody>
				                <tr>
				                  <!--Show Facebook logo on avatar, with width 200px and max height 600px-->
				                  <td><input name="bigimage_setting" id="bigimage_setting_3" value="0" {if $result.bigimage_setting == '0'}checked="checked" {/if} type="radio"></td>
													<td><label for="bigimage_setting_3">{lang_print id=650001018}</label></td>
				                </tr>
				                <tr>
				                 <!--Show Facebook logo on avatar, with max width 100px and max height 300px-->
				                	<td><input name="bigimage_setting" id="bigimage_setting_4" value="1" {if $result.bigimage_setting == '1'} checked="checked" {/if} type="radio"></td>
													<td><label for="bigimage_setting_4">{lang_print id=650001019}</label></td>
				                </tr>
				                <tr>
				                 <!--No Facebook Logo on avatar, with width 200px and max height 600px-->
				                  <td><input name="bigimage_setting" id="bigimage_setting_1" value="2" {if $result.bigimage_setting == '2'}checked="checked" {/if} type="radio"></td>
													<td><label for="bigimage_setting_1">{lang_print id=650001016}</label></td>
				                </tr>
				                <tr>
				                 <!--No Facebook Logo on avatar, with max width 100px and max height 300px-->
				                	<td><input name="bigimage_setting" id="bigimage_setting_2" value="3" {if $result.bigimage_setting == '3'} checked="checked" {/if} type="radio"></td>
													<td><label for="bigimage_setting_2">{lang_print id=650001017}</label></td>
				                </tr>
				              </tbody>
				            </table>
									</td>
				        </tr>
	        
	           
		         </table>
		        
		        </div>        
		      </td>
	      </tr>
  

	
		    <table cellpadding="0" cellspacing="0" width="600">
            <tbody>
              <tr>
                <td class="header">
									{lang_print id=650000077}
								</td>
              </tr>
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000078}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                      	<td><input name="reg_feed" id="reg_feed" value="1" {if $result.reg_feed == 1} checked="checked" {/if} type="checkbox"></td>
												<td><label for="reg_feed">{lang_print id=650000031}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000032}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='reg_feed_bundleid' value='{$result.reg_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000080}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                      	<td><input name="status_feed" id="status_feed" value="1" {if $result.status_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="status_feed">{lang_print id=650000033}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000034}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='status_feed_bundleid' value='{$result.status_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000081}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                      	<td><input name="editphoto_feed" id="editphoto_feed" value="1" {if $result.editphoto_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="editphoto_feed">{lang_print id=650000035}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000036}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='editphoto_feed_bundleid' value='{$result.editphoto_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000082}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                        <td><input name="postblog_feed" id="postblog_feed" value="1" {if $result.postblog_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="postblog_feed">{lang_print id=650000037}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000038}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='postblog_feed_bundleid' value='{$result.postblog_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000083}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                        <td><input name="newgroup_feed" id="newgroup_feed" value="1" {if $result.newgroup_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="newgroup_feed">{lang_print id=650000039}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000040}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='newgroup_feed_bundleid' value='{$result.newgroup_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
							
<!--							<tr>
								<td class="setting1">
									<b>{lang_print id=650000084}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                        <td><input name="newevent_feed" id="newevent_feed" value="1" {if $result.newevent_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="editphoto_feed">{lang_print id=650000041}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000042}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='newevent_feed_bundleid' value='{$result.newevent_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>-->
							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000085}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                        <td><input name="newpoll_feed" id="newpoll_feed" value="1" {if $result.newpoll_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="newpoll_feed">{lang_print id=650000043}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000044}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='newpoll_feed_bundleid' value='{$result.newpoll_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000086}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                        <td><input name="newalbum_feed" id="newalbum_feed" value="1" {if $result.newalbum_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="newalbum_feed">{lang_print id=650000045}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000046}
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='text' class='text' name='newalbum_feed_bundleid' value='{$result.newalbum_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000087}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                       <td><input name="postclassified_feed" id="postclassified_feed" value="1" {if $result.postclassified_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="postclassified_feed">{lang_print id=650000047}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000048}
												</td>
											</tr>
											<tr>
												<td colspan="2">
												<input type='text' class='text' name='postclassified_feed_bundleid' value='{$result.postclassified_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
              
              
           <!-- 	<tr>
								<td class="setting1">
									<b>{lang_print id=650000088}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                       <td><input name="postmusic_feed" id="postmusic_feed" value="1" {if $result.postmusic_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="postmusic_feed">{lang_print id=650000091}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000092}
												</td>
											</tr>
											<tr>
												<td colspan="2">
												<input type='text' class='text' name='postmusic_feed_bundleid' value='{$result.postmusic_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>-->
              
             <tr>
								<td class="setting1">
									<b>{lang_print id=650000089}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                       <td><input name="postvideo_feed" id="postvideo_feed" value="1" {if $result.postvideo_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="postvideo_feed">{lang_print id=650000093}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000094}
												</td>
											</tr>
											<tr>
												<td colspan="2">
												<input type='text' class='text' name='postvideo_feed_bundleid' value='{$result.postvideo_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
              </tr>
              
              
             							
							<tr>
								<td class="setting1">
									<b>{lang_print id=650000090}</b>
								</td>
							</tr>
              <tr>
                <td class="setting2">
									<table cellpadding="2" cellspacing="0">
                   	<tbody>
                      <tr>
                       <td><input name="postyoutube_feed" id="postyoutube_feed" value="1" {if $result.postyoutube_feed == 1}checked="checked" {/if} type="checkbox"></td>
												<td><label for="postyoutube_feed">{lang_print id=650000095}</label></td>
                      </tr>
											<tr>
												<td colspan="2">
													{lang_print id=650000096}
												</td>
											</tr>
											<tr>
												<td colspan="2">
												<input type='text' class='text' name='postyoutube_feed_bundleid' value='{$result.postyoutube_feed_bundleid}' size='50' maxlength='100'>
												</td>
											</tr>
                    </tbody>
                  </table>
								</td>
							 </tr>
							
            </tbody>
          </table>
          <br>
				    <input type='submit' class='button' value='{lang_print id=173}'>&nbsp;
				    <input value="Cancel" onclick="javascript:history.back(1)" type="button" class='button'>
				    <input type='hidden' name='task' value='edit'>
				    </form>
{literal}
<script>
function showContent(id) { 
    document.getElementById(id).style.display = 'block';
}

function hideContent(id) { 
    document.getElementById(id).style.display = 'none'; 
}
</script>
{/literal}
{include file='admin_footer.tpl'}