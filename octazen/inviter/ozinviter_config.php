<?php
/********************************************************************************
Unified Inviter Component
Default Configuration File

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/

global $_OZ_CONFIG;
$_OZ_CONFIG = array(

	//0=Icon selector
	//1=Jump straight to login form
	//2=Login form with icon selector at the bottom
	'selector_mode' => 0,
	
	'selector_group_icons' => FALSE,
	
	//Limit the number of icons to show by default for address book and social network.
	//A "Show All" or "Hide" link will be shown if there are more icons.
	'selector_ab_max_icons' => 100,
	'selector_sn_max_icons' => 100,
	
	//Select all contacts by default?
	'select_all_contacts' => FALSE,

	//true to ask user for their name/email, false otherwise. from_name and from_email is the default value to use if your_name and your_email is set to TRUE.
	'your_name' => FALSE,
	'your_email' => FALSE,
	'from_name' => '',
	'from_email' => '',
	
	//Use NULL/blank string for default mod implementation (only for mod/plugins)
	'mailer' => NULL,
	'invite_url' => NULL,
	'subject' => NULL,
	'text_body' => NULL,
	'text_message' => NULL,

	//True to allow personalized message, false otherwise
	'allow_personal_message' => TRUE,
	
	//True to prefer browser based authentication (Google AuthSub, Hotmai Windows Live Login, etc)
	'prefer_webauth' => TRUE,

	//True to enable manual invite
	'allow_manual_invite' => TRUE,

	//True to enable CSV/LDIF upload 
	'allow_upload' => TRUE,

	//True to enable link sharing through social bookmarks
	'allow_bookmark' => TRUE,
	
	//Desktop Importer presence: 0=No, 1=Use ActiveX if available, 2=Use Desktop EXE always
	'desktopimporter_present' => 0,
	
	//Your Desktop contacts importer decryption key
	'desktopimporter_decrypt_key' => "xxxxx",
	
	//Your Desktop contacts importer config string
	'desktopimporter_config_string' => "xxxxx",

	//Show/Hide Octazen branding
	'show_branding'=>FALSE,
);

?>