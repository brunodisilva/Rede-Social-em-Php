<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>Theme Editor</title>

	<link rel="stylesheet" href="css/application.css" type="text/css" media="all" />



<style type="text/css">
@import url("css/preview.css");
@import url("css/sliders.css");
@import url("css/controls.css");

</style>

<script type="text/javascript" src="js/application.js"></script>
<script type="text/javascript" src="js/interface.js"></script>
<script type="text/javascript" src="js/components.js"></script>
</head>

<body onLoad="top.window.focus()">

  <div id="application"> 
    <form name="profile" method="post" action="getcode.php">

                <input type='hidden' name='actions_total' id='actions_total' value='10'>

      <input type="hidden" id="action" name="action" value="generate" />
      <input type="hidden" id="in_backgroundColor" name="in_backgroundColor" value="rgb(255,255,255)" />
      <input type="hidden" id="in_backgroundImage" name="in_backgroundImage" value="none" />
      <input type="hidden" id="in_backgroundPosition" name="in_backgroundPosition" value="top left" />
      <input type="hidden" id="in_backgroundRepeat" name="in_backgroundRepeat" value="repeat" />
      <input type="hidden" id="in_backgroundAttachment" name="in_backgroundAttachment" value="fixed" />
      <input type="hidden" id="in_sectionBackgroundColor" name="in_sectionBackgroundColor" value="rgb(255,255,255)" />
      <input type="hidden" id="in_sectionBorderColor" name="in_sectionBorderColor" value="rgb(102,102,102)" />
      <input type="hidden" id="in_sectionBorderStyle" name="in_sectionBorderStyle" value="solid" />

      <input type="hidden" id="in_headingFontFamily" name="in_headingFontFamily" value="Verdana" />
      <input type="hidden" id="in_headingFontColor" name="in_headingFontColor" value="rgb(102,153,204)" />
      <input type="hidden" id="in_headingFontWeight" name="in_headingFontWeight" value="bold" />
      <input type="hidden" id="in_headingFontStyle" name="in_headingFontStyle" value="normal" />
      <input type="hidden" id="in_headingFontDecoration" name="in_headingFontDecoration" value="none" />
      <input type="hidden" id="in_nameFontFamily" name="in_nameFontFamily" value="Verdana" />
      <input type="hidden" id="in_nameFontColor" name="in_nameFontColor" value="rgb(0,0,0)" />
      <input type="hidden" id="in_nameFontWeight" name="in_nameFontWeight" value="bold" />
      <input type="hidden" id="in_nameFontStyle" name="in_nameFontStyle" value="normal" />

      <input type="hidden" id="in_nameFontDecoration" name="in_nameFontDecoration" value="none" />
      <input type="hidden" id="in_fontFamily" name="in_fontFamily" value="Verdana" />
      <input type="hidden" id="in_fontColor" name="in_fontColor" value="rgb(0,0,0)" />
      <input type="hidden" id="in_fontWeight" name="in_fontWeight" value="normal" />
      <input type="hidden" id="in_fontStyle" name="in_fontStyle" value="normal" />
      <input type="hidden" id="in_fontDecoration" name="in_fontDecoration" value="none" />
      <input type="hidden" id="in_linkFontColor" name="in_linkFontColor" value="rgb(0,51,153)" />
      <input type="hidden" id="in_linkFontWeight" name="in_linkFontWeight" value="bold" />
      <input type="hidden" id="in_linkFontStyle" name="in_linkFontStyle" value="normal" />

      <input type="hidden" id="in_linkFontDecoration" name="in_linkFontDecoration" value="none" />
      <input type="hidden" id="in_linkHoverFontColor" name="in_linkHoverFontColor" value="rgb(204,0,0)" />
      <input type="hidden" id="in_linkHoverFontWeight" name="in_linkHoverFontWeight" value="bold" />
      <input type="hidden" id="in_linkHoverFontStyle" name="in_linkHoverFontStyle" value="normal" />
      <input type="hidden" id="in_linkHoverFontDecoration" name="in_linkHoverFontDecoration" value="underline" />
      <ul id="controls">
        <li id="box_menu"> 
          <ul id="menu">
            <li id="win1_tab" class="current"><a href="javascript:void('0')" onClick="selectWin('1',this)" id="tabone">
			Body</a></li>

            <li id="win2_tab"><a href="javascript:void('0')" onClick="selectWin('2',this)">
			Box</a></li>
            <li id="win3_tab"><a href="javascript:void('0')" onClick="selectWin('3',this)">
			Menu & Header</a></li>
            <li id="win4_tab"><a href="javascript:void('0')" onClick="selectWin('4',this)">
			Text & Link</a></li>
          </ul>
        </li>
        <li id="box_layers"> 
          <ul id="layers">
            <li class="container" id="win1"> 
              <div class="kpeoption"> <strong>Background Color:</strong><br />

                <iframe name="s_bgc-picker" id="s_bgc-picker" src="js/rgbd4d5.html?rgb=255,255,255" frameborder="0"></iframe>
                <br />
              </div>
              <div class="kpeoption"> <strong>Background Image:</strong><br />
                <input type="text" class="text" id="s_bgiu" name="s_bgiu" value="Enter Image URL" size="35" maxlength="255" onFocus="if(this.value == 'Enter Image URL') this.value='';" style="width:280px;" />
                <input type="button" class="button" id="bgiApply" name="bgiApply" value="apply" onClick="kpe_update('backgroundImage', getObject('s_bgiu').value)" />
                <br />

                <span class="note">Enter the full url to your image, inlcuding 
				http:// <br> You can upload your photo at <a href="http://www.photobucket.com/"
target="_blank">Photobucket</a> or other image hosting sites.</span><br />
                
                </div>
              <div class="kpeoption"> <strong>Background Position:</strong><br />
                <div id="backgroundAlignSelect"> 
                  <div id="bgao1" class="selected" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-tl.gif" width="18" height="18" alt="Top Left" /></div>
                  <div id="bgao2" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-tc.gif" width="18" height="18" alt="Top Center" /></div>
                  <div id="bgao3" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-tr.gif" width="18" height="18" alt="Top Right" /></div>
                  <div id="bgao4" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-ml.gif" width="18" height="18" alt="Middle Left" /></div>

                  <div id="bgao5" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-mc.gif" width="18" height="18" alt="Middle Center" /></div>
                  <div id="bgao6" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-mr.gif" width="18" height="18" alt="Middle Right" /></div>
                  <div id="bgao7" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-bl.gif" width="18" height="18" alt="Bottom Left" /></div>
                  <div id="bgao8" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-bc.gif" width="18" height="18" alt="Bottom Center" /></div>
                  <div id="bgao9" onClick="kpe_align_bg(this.id);"><img src="images/icon-align-br.gif" width="18" height="18" alt="Bottom Right" /></div>
                </div>
              </div>
              <div class="kpeoption"> <strong>Background Repeat:</strong> 
                <table width="100%" cellspacing="1" cellpadding="0" border="0">

                  <tr> 
                    <td width="25%"><input type="radio" id="s_bgr" name="s_bgr" onClick="kpe_update('backgroundRepeat', this.value);" value="no-repeat" />
                      None</td>
                    <td width="25%"><input type="radio" id="s_bgr" name="s_bgr" onClick="kpe_update('backgroundRepeat', this.value);" value="repeat-x" />
                      Across</td>
                    <td width="25%"><input type="radio" id="s_bgr" name="s_bgr" onClick="kpe_update('backgroundRepeat', this.value);" value="repeat-y" />
                      Down</td>
                    <td width="25%"><input type="radio" id="s_bgr" name="s_bgr" onClick="kpe_update('backgroundRepeat', this.value);" value="repeat" checked />

                      Across &amp; Down</td>
                  </tr>
                </table>
              </div>
              <div class="kpeoption-end"> <strong>Background Attachment:</strong><br />
                <br />

                <input type="radio" id="s_bga" name="s_bga" onClick="kpe_update('backgroundAttachment', this.value);" value="fixed" checked />
                Fixed <span class="note">(image does not move when you scroll 
				up/down)</span><br />
                <input type="radio" id="s_bga" name="s_bga" onClick="kpe_update('backgroundAttachment', this.value);" value="scroll" />
                Scroll <span class="note">(image follow the page as you scroll 
				up/down)</span><br />
              </div>
            </li>
            <li class="container" id="win2"> 
              <div class="kpeoption"> <strong>Background Color:</strong><br />

                <br />
                <input type="checkbox" id="s_sbgc-trans" name="s_sbgc-trans" onClick="kpe_transparent_section()" value="transparent" />
                Fully Transparent <span class="note">(Optional)</span><br />
                <div id="s_sbgc-wrapper" style="display:block"> 
                  <iframe name="s_sbgc-picker" id="s_sbgc-picker" src="js/rgbd4d5.html?rgb=255,255,255" frameborder="0"></iframe>
                  <br />
                </div>
              </div>

              <div class="kpeoption"> 
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Border Width:</strong></th>
                    <td><div class="slider" id="s_sbw" tabIndex="1"> 
                        <input class="slider-input" id="s_sbw-input"/>
                      </div></td>
                    <th class="field"><input type="text" id="in_sectionBorderWidth" name="in_sectionBorderWidth" value="2" size="2" maxlength="2" readonly />
                      px </th>

                  </tr>
                </table>
              </div>
              <div class="kpeoption"> <strong>Border Color:</strong><br />
                <iframe name="s_sbc-picker" id="s_sbc-picker" src="js/rgb4ded.html?rgb=102,102,102" frameborder="0"></iframe>
                <br />
              </div>

              <div class="kpeoption"> <strong>Border Style:</strong><br />
                <table width="100%" cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <td width="25%"><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="solid" checked />
                      Solid</td>
                    <td width="25%"><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="dotted" />
                      Dotted</td>

                    <td width="25%"><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="dashed" />
                      Dashed</td>
                    <td width="25%"><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="double" />
                      Double</td>
                  </tr>
                  <tr> 
                    <td><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="groove" />
                      Groove</td>

                    <td><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="ridge" />
                      Ridge</td>
                    <td><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="inset" />
                      Inset</td>
                    <td><input type="radio" id="s_sbs" name="s_sbs" onClick="kpe_update('sectionBorderStyle', this.value)" value="outset" />
                      Outset</td>
                  </tr>

                </table>
              </div>
				<div class="kpeoption-end">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
				<th><strong>Opacity:</strong></th>
				<td><div class="slider" id="s_sop" tabIndex="1"><input class="slider-input" id="s_sop-input"/></div></td>
				<th class="field"><input type="text" id="in_sectionOpacity" name="in_sectionOpacity" value="100" size="3" maxlength="3" readonly />%</th>

				</tr>
				</table>
				</div>
            </li>
            <li class="container" id="win3"> 
              <div class="kpeoption"> <strong>Menu Font:</strong> 
                <table width="100%" cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <td nwrap width="25%"><input type="radio" name="nfont" onClick="kpe_update('nameFontFamily', this.value)" value="Arial" />

                      Arial</td>
                    <td nwrap width="25%"><input type="radio" name="nfont" onClick="kpe_update('nameFontFamily', this.value)" value="Courier New" />
                      Courier New</td>
            
                    <td nwrap width="25%"><input type="radio" name="nfont" onClick="kpe_update('nameFontFamily', 'sans-serif')" value="sans-serif" />
                      Sans-serif</td>

                  </tr>
                  <tr> 
                    <td nwrap><input type="radio" name="nfont" onClick="kpe_update('nameFontFamily', 'Tahoma')" value="Tahoma" />
                      Tahoma</td>
                    <td nwrap><input type="radio" name="nfont" onClick="kpe_update('nameFontFamily', 'Times New Roman')" value="Times New Roman" />
                      Times Roman</td>
        

                    <td nwrap><input type="radio" name="nfont" onClick="kpe_update('nameFontFamily', 'Verdana')" value="Verdana" checked />
                      Verdana</td>
                  </tr>
                </table>
                <table cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Font Style:</strong>&nbsp;</th>
                    <td> <div id="fontStyleSelect"> 
                        <div id="nfso1" class="selected" onClick="kpe_update('nameFontWeight', 'bold');"><img src="images/icon-font-bold.gif" width="19" height="19" alt="Bold" /></div>

                        <div id="nfso2" onClick="kpe_update('nameFontStyle', 'italic');"><img src="images/icon-font-italic.gif" width="19" height="19" alt="Italic" /></div>
                        <div id="nfso3" onClick="kpe_update('nameFontDecoration', 'underline');"><img src="images/icon-font-underline.gif" width="19" height="19" alt="Underline" /></div>
                      </div></td>
                  </tr>
                </table>
              </div>
              <div class="kpeoption"> 
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Name Size:</strong></th>

                    <td><div class="slider" id="s_nfs" tabIndex="1"> <input class="slider-input" id="s_nfs-input"/></td>
                    <th class="field"><input type="text" id="in_nameFontSize" name="in_nameFontSize" value="12" size="2" maxlength="2" readonly />
                      px</th>
                  </tr>
                </table>
              </div>
              <div class="kpeoption"> <strong>Menu Color:</strong> 
                <iframe name="s_nfc-picker" id="s_nfc-picker" src="js/rgbc645.html?rgb=0,0,0" frameborder="0"></iframe>

              </div>
              <div class="kpeoption"> <strong>Header Font:</strong><br />
                <table width="100%" cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <td nwrap width="25%"><input type="radio" name="hfont" onClick="kpe_update('headingFontFamily', this.value)" value="Arial" />
                      Arial</td>
                    <td nwrap width="25%"><input type="radio" name="hfont" onClick="kpe_update('headingFontFamily', this.value)" value="Courier New" />
                      Courier New</td>

                 
                    <td nwrap width="25%"><input type="radio" name="hfont" onClick="kpe_update('headingFontFamily', 'sans-serif')" value="sans-serif" />
                      Sans-serif</td>
                  </tr>
                  <tr> 
                    <td nwrap><input type="radio" name="hfont" onClick="kpe_update('headingFontFamily', 'Tahoma')" value="Tahoma" />
                      Tahoma</td>

                    <td nwrap><input type="radio" name="hfont" onClick="kpe_update('headingFontFamily', 'Times New Roman')" value="Times New Roman" />
                      Times Roman</td>
                  
                    <td nwrap><input type="radio" name="hfont" onClick="kpe_update('headingFontFamily', 'Verdana')" value="Verdana" checked />
                      Verdana</td>
                  </tr>

                </table>
                <table cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Font Style:</strong>&nbsp;</th>
                    <td> <div id="fontStyleSelect"> 
                        <div id="hfso1" class="selected" onClick="kpe_update('headingFontWeight', 'bold');"><img src="images/icon-font-bold.gif" width="19" height="19" alt="Bold" /></div>
                        <div id="hfso2" onClick="kpe_update('headingFontStyle', 'italic');"><img src="images/icon-font-italic.gif" width="19" height="19" alt="Italic" /></div>
                        <div id="hfso3" onClick="kpe_update('headingFontDecoration', 'underline');"><img src="images/icon-font-underline.gif" width="19" height="19" alt="Underline" /></div>
                      </div></td>

                  </tr>
                </table>
              </div>
              <div class="kpeoption"> 
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Header Size:</strong></th>
                    <td><div class="slider" id="s_hfs" tabIndex="1"> 
                        <input class="slider-input" id="s_hfs-input"/>
                      </div></td>

                    <th class="field"><input type="text" id="in_headingFontSize" name="in_headingFontSize" value="12" size="2" maxlength="2" readonly />
                      px </th>
                  </tr>
                </table>
              </div>
              <div class="kpeoption-end"> <strong>Header Color:</strong><br />
                <iframe name="s_hfc-picker" id="s_hfc-picker" src="js/rgbbcc1.html?rgb=102,153,204" frameborder="0"></iframe>

                <br />
              </div>
            </li>
            <li class="container" id="win4"> 
              <div class="kpeoption"> <strong>Main Font:</strong> 
                <table width="100%" cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <td nwrap width="25%"><input type="radio" name="mfont" onClick="kpe_update('fontFamily', this.value)" value="Arial" />
                      Arial</td>

                    <td nwrap width="25%"><input type="radio" name="mfont" onClick="kpe_update('fontFamily', this.value)" value="Courier New" />
                      Courier New</td>
                    
                    <td nwrap width="25%"><input type="radio" name="mfont" onClick="kpe_update('fontFamily', 'sans-serif')" value="sans-serif" />
                      Sans-serif</td>
                  </tr>

                  <tr> 
                    <td nwrap><input type="radio" name="mfont" onClick="kpe_update('fontFamily', 'Tahoma')" value="Tahoma" />
                      Tahoma</td>
                    <td nwrap><input type="radio" name="mfont" onClick="kpe_update('fontFamily', 'Times New Roman')" value="Times New Roman" />
                      Times Roman</td>
                 
                    <td nwrap><input type="radio" name="mfont" onClick="kpe_update('fontFamily', 'Verdana')" value="Verdana" checked />

                      Verdana</td>
                  </tr>
                </table>
                <table cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Font Style:</strong>&nbsp;</th>
                    <td> <div id="fontStyleSelect"> 
                        <div id="fso1" onClick="kpe_update('fontWeight', 'bold');"><img src="images/icon-font-bold.gif" width="19" height="19" alt="Bold" /></div>

                        <div id="fso2" onClick="kpe_update('fontStyle', 'italic');"><img src="images/icon-font-italic.gif" width="19" height="19" alt="Italic" /></div>
                        <div id="fso3" onClick="kpe_update('fontDecoration', 'underline');"><img src="images/icon-font-underline.gif" width="19" height="19" alt="Underline" /></div>
                      </div></td>
                  </tr>
                </table>
              </div>
              <div class="kpeoption"> 
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Text Size:</strong></th>

                    <td><div class="slider" id="s_mfs" tabIndex="1"> 
                        <input class="slider-input" id="s_mfs-input" />
                      </div></td>
                    <th class="field"><input type="text" id="in_fontSize" name="in_fontSize" value="12" size="2" maxlength="2" readonly />
                      px </th>
                  </tr>
                </table>
              </div>
              <div class="kpeoption"> <strong>Text Color:</strong><br />

                <iframe name="s_mfc-picker" id="s_mfc-picker" src="js/rgbbcc1.html?rgb=102,153,204" frameborder="0"></iframe>
                <br />
              </div>
              <div class="kpeoption"> <strong>Link Color:</strong><br />
                <iframe name="s_lfc-picker" id="s_lfc-picker" src="js/rgb1bb9.html?rgb=0,51,153" frameborder="0"></iframe>
                <br />
                <table cellspacing="1" cellpadding="0" border="0">

                  <tr> 
                    <th><strong>Link Style:</strong>&nbsp;</th>
                    <td> <div id="fontStyleSelect"> 
                        <div id="lso1" class="selected" onClick="kpe_update('linkFontWeight', 'bold');"><img src="images/icon-font-bold.gif" width="19" height="19" alt="Bold" /></div>
                        <div id="lso2" onClick="kpe_update('linkFontStyle', 'italic');"><img src="images/icon-font-italic.gif" width="19" height="19" alt="Italic" /></div>
                        <div id="lso3" onClick="kpe_update('linkFontDecoration', 'underline');"><img src="images/icon-font-underline.gif" width="19" height="19" alt="Underline" /></div>
                      </div></td>
                  </tr>
                </table>

              </div>
              <div class="kpeoption-end"> <strong>Link Hover Color:</strong><br />
                <iframe name="s_lhfc-picker" id="s_lhfc-picker" src="js/rgbbe36.html?rgb=204,0,0" frameborder="0"></iframe>
                <br />
                <table cellspacing="1" cellpadding="0" border="0">
                  <tr> 
                    <th><strong>Link Hover Style:</strong>&nbsp;</th>

                    <td> <div id="fontStyleSelect"> 
                        <div id="lhso1" class="selected" onClick="kpe_update('linkHoverFontWeight', 'bold');"><img src="images/icon-font-bold.gif" width="19" height="19" alt="Bold" /></div>
                        <div id="lhso2" onClick="kpe_update('linkHoverFontStyle', 'italic');"><img src="images/icon-font-italic.gif" width="19" height="19" alt="Italic" /></div>
                        <div id="lhso3" class="selected" onClick="kpe_update('linkHoverFontDecoration', 'underline');"><img src="images/icon-font-underline.gif" width="19" height="19" alt="Underline" /></div>
                      </div></td>
                  </tr>
                </table>
              </div>
            </li>

          </ul>
        </li>
      </ul>
      <ul id="preview">
        <li id="shaddow">&nbsp;</li>
        <li id="pane"> 
          <ul>
            <li class="ptitle">Preview: copyright &copy; 2009 proudsillimanians.com</li>
            <li class="cutout"> 
              <div id="mysbody"> <table>
				<tr>
					<td valign="top" colspan="2">

                     <div id="mysname"> Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu Menu</div></td>
				<br>
				</tr>
				<td valign="top" align='left'>

                      Your Profile
               
                
                  <table cellspacing="0" cellpadding="0" border="0" width='100%'>

                    
                    <tr> 
                      <td><div id="mystable1"><img 'photo' src='../layout-editor/images/layout.jpg' border='0' width='170'></div></td>
           
                    </tr>

                      </table>
                
             <div id="mysspacer">&nbsp;</div>
                <div id="mystable2"> 
         
                  <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
      <td 'profile_menu1'><a id="myslink" href="javascript:void('0');" onMouseOver="kpe_link('hover')" onMouseOut="kpe_link('')"><img src='./images/icons/browsefriends16.gif' 'icon' width='12' border='0'>Status,statistics,mood,other stats</a></td>
      </tr>
     
                      <tr>

        <td 'profile_menu1'><a id="myslink" href="javascript:void('0');" onMouseOver="kpe_link('hover')" onMouseOut="kpe_link('')"><img src='./images/icons/addfriend16.gif' 'icon' width='12' border='0'></a></td>
        </tr>
          
         
        <tr>
    <td 'profile_menu1'><a id="myslink" href="javascript:void('0');" onMouseOver="kpe_link('hover')" onMouseOut="kpe_link('')"><img src='./images/icons/report16.gif' 'icon' width='12' border='0'></a></td>
    </tr>

                      <tr>
        <td 'profile_menu1'><a id="myslink" href="javascript:void('0');" onMouseOver="kpe_link('hover')" onMouseOut="kpe_link('')"><img src='./images/icons/block16.gif' 'icon' width='12' border='0'></a></td>

        </tr>
          
    
                  </table>
                </div>
                 <div id="mysspacer">&nbsp;</div>
                <div id="mystable5"> 
                  <table cellpadding='0' cellspacing='0' width='170' style='margin-bottom: 10px;' height="180">
      <tr>
		<td 'header' valign='top' height="19" border='1' width='170' style="border-style: solid; border-width: 1px">
        <div id="mystitle">Header</div></td></tr>

      <tr><td 'header' valign='top'><div id="mystext">
		TextTextTextTextTextText</div></td></tr>

      </table>
                </div>
</td><td width='200'><div id="mystable3"> 
                  <table cellpadding='0' cellspacing='0' width='67%' style='margin-bottom: 10px;' height="180">
      <tr><td 'header' valign='top'>
        </td></tr>

      </table>
                </div>
                <div id="mysspacer">&nbsp;</div>
                <div id="mystable4"> 
                  <table cellpadding='0' cellspacing='0' width='67%' style='margin-bottom: 10px;' height="180">
      <tr><td 'header' valign='top'>
	    <div id="mystitle">Profile Information</div></td></tr>

      </table>
                </div></td></table>
              </div>
            </li>
            <li class="submit"> 
              <div><a href="javascript:kpe_generate();">&nbsp;</a></div>

            </li>
          </ul>
        </li>
      </ul>
    </form>
  </div>

</div>


<script language="JavaScript" src="js/tt.js">

</script>
</body>


</html>