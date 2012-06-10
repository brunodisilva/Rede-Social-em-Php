<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>Theme Editor</title>

	<link rel="stylesheet" href="css/application.css" type="text/css" media="all" />
</head>

<body>



  <div id="application">
    <div id="window">
      <div class="innerwrap"> <div class="content">
        <h1>Congratulations, your layout is complete!</h1>
        <strong>Copy (CTR+C) the CSS code below and paste (CTR+V) it into the "Profile Style" box, then click the "Save Changes" button</strong>
        <ol>
          

        <strong>CSS Code:</strong><br />
        <textarea id="codebox" name="codebox" class="codebox" cols="40" rows="20" onFocus="this.select()" onClick="this.select()" readonly>


/* GLOBAL STYLES */



body {

	background-color: <?=$_POST['in_backgroundColor'];?>;
	background-image: url(<?=$_POST['s_bgiu'];?>);
	background-position: <?=$_POST['in_backgroundPosition'];?>;
	background-repeat: <?=$_POST['in_backgroundRepeat'];?>;
	background-attachment: <?=$_POST['in_backgroundAttachment'];?>;
    }





/* Top Menu Links */

a.top_menu_item:link { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>; }

a.top_menu_item:visited { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>;}

a.top_menu_item:hover { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>; }
	


/* LOGGED IN Menu Links */

a.menu_item:link { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>;
	}

a.menu_item:visited { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>;
	}

a.menu_item:hover { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>;
	}
	



/* Global Links */

a:link { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>;
	}

a:visited {	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>;
	}

a:hover { 	font-family: <?=$_POST['in_fontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_fontColor'];?>;
	font-weight: <?=$_POST['in_fontWeight'];?>;
	font-style: <?=$_POST['in_fontStyle'];?>;
	text-decoration: <?=$_POST['in_fontDecoration'];?>;
	 }



/* Override All Links */

a {

    font-weight: <?=$_POST['in_nameFontWeight'];?>;

}





/* Content Box */

td.content {
	border: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;
	background-color: transparent;

	/* distance between outer borders and content box */

	padding: 6px;

}



/* Applies to most interior content */

div, td {

	background:transparent;

	font-family: <?=$_POST['in_nameFontFamily'];?>;
	font-size: <?=$_POST['in_nameFontSize'];?>px;
	color: <?=$_POST['in_nameFontColor'];?>;
	font-weight: <?=$_POST['in_nameFontWeight'];?>;
	font-style: <?=$_POST['in_nameFontStyle'];?>;
	text-decoration: <?=$_POST['in_nameFontDecoration'];?>;
	padding: 1px; /* distance between global element borders and their content */
    line-height: 140%; /* distance between lines of text */

    /* text-align:justify; */

}





/* TOP Menu */

td.topbar1 {

	padding-bottom: 0px;
    padding-left: 100px;
    padding-right: 100px;
    background: transparent;

}

td.topbar2, td.topbar2_right {

    background:transparent;
	background-color: <?=$_POST['in_backgroundColor'];?>;
	font-family: <?=$_POST['in_nameFontFamily'];?>;
	font-size: <?=$_POST['in_nameFontSize'];?>px;
	color: <?=$_POST['in_nameFontColor'];?>;
	font-weight: <?=$_POST['in_nameFontWeight'];?>;
	font-style: <?=$_POST['in_nameFontStyle'];?>;
	text-decoration: <?=$_POST['in_nameFontDecoration'];?>;
	padding: 10px 8px 10px 20px; 
    

}



/* User Logged In Menu */

td.menu {

	background: transparent;
	background-color: <?=$_POST['in_backgroundColor'];?>;
	filter:alpha(<?=$_POST['in_sectionOpacity'];?>);
	-moz-opacity:0.;
	opacity: <?=$_POST['in_sectionOpacity'];?>;
	-khtml-opacity:0.;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;

}



/* User Logged in Menu Items */

td.menu_item {

	background: transparent;

	padding-top: 5px;

	padding-right: 6px;

	padding-bottom: 5px;

	padding-left: 6px;

	font-size: <?=$_POST['in_nameFontSize'];?>px;
	color: <?=$_POST['in_nameFontColor'];?>;
	font-weight: <?=$_POST['in_nameFontWeight'];?>;
	font-style: <?=$_POST['in_nameFontStyle'];?>;
	text-decoration: <?=$_POST['in_nameFontDecoration'];?>;

}



/* User Logged in Menu Shadows */

td.shadow {

	display: none; 

    visibility: hidden;

}





td.shadow img {

	display: none; 

    visibility: hidden;

}



/* Yourname's profile Bar */

div.page_header {

	font-size: <?=$_POST['in_nameFontSize'];?>px;
	color: <?=$_POST['in_nameFontColor'];?>;
	font-weight: <?=$_POST['in_nameFontWeight'];?>;
	font-style: <?=$_POST['in_nameFontStyle'];?>;
	text-decoration: <?=$_POST['in_nameFontDecoration'];?>;
	background: transparent;
	margin-bottom: 0px;
	padding-top: 6px;
	padding-left: 6px;

}



/* Global Headers - Titles */

td.header {
	background: transparent;
	padding: 4px 2px 4px 4px;
	font-size: <?=$_POST['in_nameFontSize'];?>px;
	color: <?=$_POST['in_nameFontColor'];?>;
	font-weight: <?=$_POST['in_nameFontWeight'];?>;
	font-style: <?=$_POST['in_nameFontStyle'];?>;
	text-decoration: <?=$_POST['in_nameFontDecoration'];?>;
	background-color: <?=$_POST['in_backgroundColor'];?>;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;


}







textarea {

	color: <?=$_POST['in_nameFontColor'];?>;
	height:100px;
	border: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;
	padding: 12px;
	font-size: 12px;

}



img.signup_code {

	background: #ffffff;

	padding: 0px;

	border-width: 2px;

	border-color: #FEB3D2;

}



#dhtmltooltip {

	background: #555555;

	border: 1px solid #AAAAAA;

}











/* PROFILE STYLES */



/* Profile box */

td.profile {

	background-color: <?=$_POST['in_sectionBackgroundColor'];?>;
	filter:alpha(<?=$_POST['in_sectionOpacity'];?>);
	-moz-opacity:0.;
	opacity: <?=$_POST['in_sectionOpacity'];?>;
	-khtml-opacity:0.;
	border: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;


	/* padding values to vary the distance between interior borders and their content */

	padding-top: 12px;

	padding-right: 22px;

	padding-bottom: 12px; 

	padding-left: 22px;

}



/* Recent Activity */

div.profile_action  {



}



/* Profile Photo */

td.profile_photo {

	padding: 0px;
	padding-bottom: 8px;
	border: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;
	background-color: transparent;
}



img.photo {

	padding: 4px;

    border-color: transparent;

	

}



/* Menu Options (below your profile image) */

table.profile_menu {

	border: 0px;

} 



td.profile_menu1 a {

	background-color: <?=$_POST['in_sectionBackgroundColor'];?>;
	border-bottom: 1px solid #444444;
	padding: 5px 5px 5px 7px;
	font-family: <?=$_POST['in_headingFontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_headingFontColor'];?>;
	font-weight: <?=$_POST['in_headingFontWeight'];?>;
	font-style: <?=$_POST['in_headingFontStyle'];?>;
	text-decoration: <?=$_POST['in_headingFontDecoration'];?>;

}



td.profile_menu1 a:hover {

	background-color: <?=$_POST['in_sectionBackgroundColor'];?>;
	border-bottom: 1px solid #444444;
	padding: 5px 5px 5px 7px;
	font-family: <?=$_POST['in_headingFontFamily'];?>;
	font-size: <?=$_POST['in_fontSize'];?>px;
	color: <?=$_POST['in_headingFontColor'];?>;
	font-weight: <?=$_POST['in_headingFontWeight'];?>;
	font-style: <?=$_POST['in_headingFontStyle'];?>;
	text-decoration: <?=$_POST['in_headingFontDecoration'];?>;


}


/* Comments Section */

td.profile_postcomment {

	padding: 8px;


	background-color: <?=$_POST['in_sectionBackgroundColor'];?>;
	filter:alpha(<?=$_POST['in_sectionOpacity'];?>);
	-moz-opacity:0.;
	opacity: <?=$_POST['in_sectionOpacity'];?>;
	-khtml-opacity:0.;
	border: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;
}



td.profile_comment_author {

	padding: 5px 7px 5px 7px;

	background-color: <?=$_POST['in_sectionBackgroundColor'];?>;
	filter:alpha(<?=$_POST['in_sectionOpacity'];?>);
	-moz-opacity:0.;
	opacity: <?=$_POST['in_sectionOpacity'];?>;
	-khtml-opacity:0.;
	border: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-width: <?=$_POST['in_sectionBorderWidth'];?>px;
	border-color: <?=$_POST['in_sectionBorderColor'];?>;
	border-style: <?=$_POST['in_sectionBorderStyle'];?>;

}



/* image verification input */

input.text, input.text_small {

	border-color: #666666;

	font-size: 12px;

	color: #cccccc;

	background-color: #444444;

}



/* Events Section */

div.profile_event_spacer {

	border-top: 2px solid #555555; 

 	margin: 0px 0px 0px 0px;

    padding: 4px;

}



td.profile_event_popup_title {

	font-size: 11pt;

	vertical-align: bottom;

	font-weight: bold;

    padding: 10px;

}



td.profile_event_popup2 {

	background: #ffffff;

	width: 640px; 

	padding: 8px;

}



td.profile_event_transparent {

	background: #000000;

	opacity: 0.5; 

	filter: alpha(opacity=20); 

	-moz-opacity: 0.2;

}



div.profile_blogentry_date {

	color: ;

	

}





</textarea><div><a href='/layout-editor/'><input type=button value="Go Back"></a></div></div>Copyright &copy; 2009 <br> proudsillimanians.com</br></br></br></body></html>
