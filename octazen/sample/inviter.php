<?php
/********************************************************************************
Sample Application (Unified Inviter Component Demo)

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2008 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/

//Include the core library and inviter component
include (dirname(__FILE__).'/../abimporter/abi.php');
include (dirname(__FILE__).'/../inviter/ozinviter.php');

//Set captcha folder and uri path (if not set, create a "captcha" subdirectory in current directory"
oz_set_config('captcha_file_path', dirname(__FILE__).'/../inviter/captcha');
oz_set_config('captcha_uri_path', '../inviter/captcha');
oz_set_config('facebook.post_to_wall_only', true);

//Override inviter default settings. Full list is in ozinviter_config.php
ozi_set_config('selector_ab_max_icons', 100);
ozi_set_config('selector_sn_max_icons', 100);
ozi_set_config('selector_group_icons', FALSE);
ozi_set_config('facebook_classicmode', FALSE);


//--------------------------------------------------
//THIS IS A CUSTOMIZABLE FUNCTION!
//
//Generate the invite message. You can modify this 
//to generate messages with personalized links, etc
//--------------------------------------------------
function oz_get_invite_message ($from_name=NULL,$from_email=NULL,$personal_message=NULL)
{
	//NOTE: You can build actual messages in real time containing referral/member id here.
	//{PERSONALMESSAGE} is replaced with the personal message if present, or blank otherwise.

	$url = 'http://www.google.com/';
	
	if ($personal_message==NULL) $personal_message='';
	$msg = array(
		'subject'=>"Test message: Inviter Component!",
		'text_body'=>"This is a test message.   Hi! \r\n\nCome and take a look at a new interesting website ".$url.".\r\n\r\n".$personal_message,
		'html_body'=>"This is a test message.   <b>Hi!</b><br/><br/>Come and take a look at a new interesting website <a href='".$url."'>".$url."</a>.<br/><br/>".htmlentities($personal_message,ENT_COMPAT,'UTF-8'),
		'url'=>$url
	);
	return $msg;
}



//--------------------------------------------------
//THIS IS A CUSTOMIZABLE FUNCTION!
//
//Send email to multiple recipients. 
//--------------------------------------------------
function oz_send_emails ($from_name,$from_email,&$contacts, $personal_message)
{
	$from_name = 'MyWebsite';
	$from_email = 'invites@mywebsite.com';
	
	$msg = oz_get_invite_message($from_name,$from_email,$personal_message);
	$subject = $msg['subject'];
	$text_body = $msg['text_body'];
	$html_body = $msg['html_body'];
	
	//Disable email delivery if tested from xxx.octazen.com
	$host = $_SERVER['HTTP_HOST'];
	if (!empty($host) && strpos(strtolower($host),"octazen.com")!==FALSE) {
		foreach ($contacts as $c) 
			echo "[Demo does not send email to ".$c['email']."<br/>\r\n";
	}
	else {
		//Deliver email using PHP's mail() function (using HTML mail)
		$html = true;	//Set to true to send html mail, false for text mail
		if (is_callable('mb_encode_mimeheader')) $from_name = mb_encode_mimeheader($from_name,"UTF-8", "Q");
		$headers = "From: \"$from_name\" <$from_email>\r\n";
		if ($html) $headers .= "Content-Type: text/html;charset=utf-8\r\nMIME-Version: 1.0\r\n";	//Send HTML part
		foreach ($contacts as $c) {
			if ($html) mail($c['email'], $subject, $html_body, $headers);
			else mail($c['email'], $subject, $text_body, $headers);
		}
	}
}


//--------------------------------------------------
//THIS IS A CUSTOMIZABLE FUNCTION!
//
//Filter/modify contacts list
//
//$contacts is a reference to an array of Contacts, where each Contact is an associative array containing the following values:
//
//	'name' => Name of the contact (may be blank)
//	'email' => Email address of the contact (only for email contacts)
//	'id' => An identifier for the contact (email address or social network user ID)
//	'uid' => Social network user ID of the contact (not present in pure email contact)
//	'image' => Absolute url to the thumbnail image of the contact (optional)
//
//This function can then perform the following
//	1) Remove/inject contacts into $contacts
//	2) For each contact, inject special attributes, as follows
//
//		'x-nocheckbox' => If set to true, disables the ability for the user to select the contact. Must be set to true if hyperlinks are present in contact row.
//		'x-namehtml' => If set, this is html snippet used in place of the name for display. You may modify the html code to generate hyperlinks, etc.
//		'x-emailhtml' => If set, this is html snippet used in place of the email for display. 
//
//This is useful in cases where the contact is already a member of the website, and we would prefer to provide
//the user the option to add the contact as a friend rather than sending an invitation emai.
//--------------------------------------------------
function oz_filter_contacts (&$contacts)
{
	$count = 0;
	$n = count($contacts);
	for ($i=0; $i<$n; $i++) {
		$c = &$contacts[$i];
		//The following disables selection checkbox for the contact
		//$c['x-nocheckbox'] = true;
		
		//The following makes a name hyperlinked (you can also inject buttons,etc)
		//$c['x-namehtml']='<a href="http://www.google.com" target="_blank">'.htmlentities(isset($c['name']) ? $c['name'] : '(no name)',ENT_COMPAT,'UTF-8').'</a>';

		//For Octazen's online demo, we're adding thumbnail, removing checkbox, creating hyperlink for the first 2 contacts
		$host = $_SERVER['HTTP_HOST'];
		if (!empty($host) && strpos(strtolower($host),"octazen.com")!==FALSE) {
			//If this is an email contact (not a social contact),
			if (!isset($c['uid']) && $count<2) {
				//Let's assume that this user is an existing member of the website. 
				//We'll hyperlink the name, remove the checkbox, and add a profile image.
				$c['x-nocheckbox']=true;
				$c['x-namehtml']='<a href="http://www.google.com" target="_blank">'.htmlentities(isset($c['name']) ? $c['name'] : '',ENT_COMPAT,'UTF-8').'</a>';
				$c['image'] = 'http://static.ak.fbcdn.net/pics/q_silhouette.gif';
				$count++;
			}
			else {
				//No change to contact
			}
		}
	}
}


	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Octazen Inviter Component Demo</title>
</head>
<body>
<!-- INCLUDE CSS FOR THE COMPONENT -->
<link type="text/css" href="../inviter/res/style.css" rel="stylesheet" />

<style type="text/css">
body, select, input, textarea {font-family:Arial, Helvetica, sans-serif;font-size: 12px;}

/* Set width of inviter component to 400px */
#oz_inviter {border: solid 1px #999999;padding: 0px;	margin: 10px;width: 500px;}

/* Define height of contacts table (full height if undefined) */
.oz_contacts_table {height: 350px;}

#oz_inviter a {text-decoration:none}
#oz_inviter a:hover {text-decoration:underline}

</style>


<table>
<tr>
<td valign="top">


<!--- RENDER THE INVITER COMPONENT --->
<div id="oz_inviter">
<?php
//Param1: URI path to resource (inviter/res) folder, absolute or relative to this page.
//Param2: File path to resource (inviter/res) folder, absolute or relative to this PHP file.
echo oz_render_inviter('../inviter/res',dirname(__FILE__).'/../inviter/res');
?>
<div id="oz_footer">Powered by <a href="http://www.octazen.com" target="_blank">Octazen Solutions Invite Engine</a></div>
</div>


</td>
<td valign="top">
<!-- YELLOW BOX (FOR DEMONSTRATION) -->
<div id="oz_callback" style="background-color:#FFFFCC; border: solid 1px #000000; margin: 10px; padding: 10px; "></div>
<script>
//Javascript callback when different view is selected
function ozOnViewChanged(view)
{
	var ele = document.getElementById("oz_callback");
	if (view=="selector") ele.innerHTML="<b>Welcome to the Octazen Demo for the PHP Invite Component!</b><br/><br/> This yellow box is updated through a callback that you may customize to provide users with additional instructions.<br/><br/>To begin, select a source of contacts below.";
	else if (view=="upload") ele.innerHTML="<b>DEMO HELP</b><br/><br/>Here you can upload Outlook CSV, Outlook Express CSV, Windows Mail CSV, and Mozilla Thunderbird LDIF. If you have the ActiveX/Desktop importer, the option for fast import is available as well.";
	else if (view=="manual") ele.innerHTML="<b>DEMO HELP</b><br/><br/>Enter the emails of your contacts whom you'd like to invite.";
	else if (view=="login") ele.innerHTML="<b>DEMO HELP</b><br/><br/>You will need to login before we can fetch your contacts.";
	else if (view=="contacts") ele.innerHTML="<b>DEMO HELP</b><br/><br/>Select the contacts whom you wish to invite. The invite system allows you to perform filtering on the contacts to disable the checkbox, or even inject HTML codes such as a 'View Profile' button if the user is already a member of the site. The personal message is optional and can be disabled.";
	else if (view=="captcha") ele.innerHTML="<b>DEMO HELP</b><br/><br/>Some websites or social networks require that the user answer a captcha challenge to prove the he/she is a person and not a computer. Your users may answer the challenges here.";
	else if (view=="bookmark") ele.innerHTML="<b>DEMO HELP</b><br/><br/>We also allow the user to share specific links in social networks or social bookmarks as postings.";
	else if (view=="finished") ele.innerHTML="<b>DEMO HELP</b><br/><br/>And you're done! For more information, go to <a href='http://www.octazen.com'>http://www.octazen.com</a>";
	else if (view=="submitting") ele.innerHTML="Please wait. Submitting data...";
	else ele.innerHTML="Unknown view: "+view;
	
}
//Javascript callback when a service is selected
function ozOnServiceSelected(svc)
{
}
</script>

</td>
</tr>
</table>

</body>
</html>
