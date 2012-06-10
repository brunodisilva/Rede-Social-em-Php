<?php
/********************************************************************************
Sample Application (Webmail Address Book)

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2008 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/
include_once("../abimporter/abi.php");



if (file_exists("recaptchalib.php")) include_once("recaptchalib.php");
//To use recaptcha, please define the variables $publickey and $privatekey
if (file_exists("recaptchakeys.php")) include_once("recaptchakeys.php");
//$publickey = "...";
//$privatekey = "...";

//Allow script to run for up to 90 seconds
@set_time_limit (90);

$email = isset($_REQUEST['email']) ? $email=$_REQUEST['email'] : '';
$pass = isset($_REQUEST['password']) ? $pass=$_REQUEST['password'] : '';
$contactlist = null;
$errmsg = '';
if (!isset($email)) $email='';
if (!isset($pass)) $pass='';
if (isset($_REQUEST['sendInvite']) && $_REQUEST['sendInvite']='Send invite' && isset($_REQUEST['emails'])) {
 
	$host = $_SERVER['HTTP_HOST'];
	//Disable email delivery if tested from xxx.octazen.com
	if (!empty($host) && strpos(strtolower($host),"octazen.com")>0) {
		echo "<div style='font-size: 20px; color:#FF0000'>Email sending disabled in demo</div><br/>\r\n";
	}
	else {
	 	////////////////////////////////////////////////////////
	 	//THIS IS THE SECTION TO SEND INVITES AN EMAIL. CUSTOMIZE TO FIT YOUR NEEDS!
	 	////////////////////////////////////////////////////////
	 	$emails = $_REQUEST['emails'];
		$subject = "Invitation from $email";
		$headers = 'From: '.$email;
		$emails = $_REQUEST['emails'];
		foreach ($emails as $to) {
			list($to,$name) = split(':::',$to,2);
			$message = "Hi $name, $email would like you to take a look at this site! http://www.octazen.com\r\n\r\n";
			//echo "[TO=$to,SUBJECT=$subject,HEADER=$headers,MESSAGE=$message]";
			mail($to, $subject, $message, $headers);
			echo "Mail sent to $name ($to)<br/>\r\n";
		}
	}
 
}
else if (isset($_REQUEST['post'])) {
 
 	////////////////////////////////////////////////////////
 	//THIS IS THE SECTION TO IMPORT CONTACTS
 	////////////////////////////////////////////////////////
	$validCaptcha = true;
	if (function_exists('recaptcha_check_answer') && isset($privatekey)) {
		if ($_POST["recaptcha_response_field"]) {
			$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);
			$validCaptcha = $resp->is_valid;
		}
		else {
			$validCaptcha = false;
		}
	}

	if ($validCaptcha) {
		$res = abi_fetch_contacts($email,$pass);
	
		if ($res==_ABI_AUTHENTICATION_FAILED) {
			$errmsg='Bad user name or password';
		}
		else if ($res==_ABI_FAILED) {
			$errmsg='Server error';
		}
		else if ($res==_ABI_UNSUPPORTED) {
			$errmsg='Unsupported webmail';
		}
		else if ($res==_ABI_CAPTCHA_RAISED) {
			$errmsg='Captcha challenge was raised during login';
		}
		else if ($res==_ABI_USER_INPUT_REQUIRED) {
		    echo 'Need to answer some questions in the webmail service';
		}
		else if (is_array($res)) {
			$contactlist = $res;
			$contactlist = abi_dedupe_contacts_by_email($contactlist);
			$contactlist = abi_sort_contacts_by_name($contactlist);
		}
		else {
			$errmsg='Unknown error';
		}
	} else {
		//set the error code so that we can display it
		$errmsg="Please enter a valid answer to the captcha challenge";
	}
 	
 	////////////////////////////////////////////////////////
}

if (function_exists('recaptcha_check_answer') && isset($privatekey) && isset($privatekey)) {
	$captchaHtml = recaptcha_get_html($publickey);
}
else {
	$captchaHtml = '';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contacts Importer</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
h1 {
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: 1px;
	border-left-width: 0px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #FF0000;
	border-right-color: #FF0000;
	border-bottom-color: #FF0000;
	border-left-color: #FF0000;
}
.table {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	background-color: #FFFFFF;
	border-top-width: 1px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #DFDFDF;
	border-right-color: #DFDFDF;
	border-bottom-color: #DFDFDF;
	border-left-color: #DFDFDF;
}
.table th {
	background-color: #C0C0C0;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #DFDFDF;
	border-right-color: #999999;
	border-bottom-color: #999999;
	border-left-color: #DFDFDF;
	margin: 0px;
	padding: 2px 10px;
}
.table td {
	border-top-width: 0px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 0px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-right-color: #CCCCCC;
	border-bottom-color: #CCCCCC;
	margin: 0px;
	padding: 2px 10px;
	vertical-align: top;
}
#footer {
	font-size: 11px;
	color: #666666;
	text-align: right;
	padding-top: 3px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	border-top-width: 1px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: solid;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	border-top-color: #CCCCCC;
	border-right-color: #CCCCCC;
	border-bottom-color: #CCCCCC;
	border-left-color: #CCCCCC;
	margin-top: 10px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
}
-->
    </style>
</head>

<body>
<h1>Contacts Importer</h1>
<form name="f1" action="" method="post">
<table>
<input type="hidden" name="post" value="1" />
  <tr valign="top">
    <td><div align="right"><strong> Your email address</strong></div></td>
    <td>        <input name="email" type="text" size="40" value="<?php echo htmlentities($email) ?>" tabindex="1"/>    
       [ <a href="http://www.octazen.com/list" target="_blank">Which webmails are supported?</a> ] <br />    
        For LinkedIn accounts, add &quot;.linkedin&quot; behind your email address (eg. myemail@gmail.com.linkedin). 
        <br />
        For Plaxo accounts, add &quot;.plaxo&quot; behind your email address (eg. myemail@gmail.com.plaxo). <br />
        <br />          </td></tr>
  <tr valign="top">
    <td><div align="right"><strong> Password</strong></div></td>
    <td>
      <input name="password" type="password" id="password" size="40"  value="<?php echo htmlentities($pass) ?>" tabindex="2"/> 
      (Will not be saved) 
</td>
  </tr>
  <tr valign="top">
    <td>&nbsp;</td>
    <td>
    <?php echo $captchaHtml ?>
	<input type="submit" name="Submit" value="Fetch" tabindex="3" /></td>
  </tr>
  <tr valign="top">
    <td></td>
    <td><p><a href="importercsv.php"><br />
      </a>[ <a href="importercsv.php">I want to import Outlook/Outlook Express/Thunderbird CSV/LDIF instead</a> ] </p>
      </td>
  </tr>
  <tr valign="top">
    <td></td>
    <td><strong><font color="#FF0000" size="3"><?php echo htmlentities($errmsg) ?></font></strong></td>
  </tr>
</table>
<?php
////////////////////////////////////////////////////////
//PRINT OUT TABLE OF RETRIEVED CONTACTS
////////////////////////////////////////////////////////
if (isset($contactlist)) {
?>
<script>
function toggleAll(cb) {
    var val = cb.checked;
	var frm = document.f1;
	var len = frm.elements.length;
	var i=0;
	for( i=0 ; i<len ; i++) {
		if (frm.elements[i].name=='emails[]') {
			frm.elements[i].checked=val;
		}
	}
}
</script>
<table border="0" cellspacing="0" cellpadding="0" class="table">
  <tr>
    <th scope="col"><input type="checkbox" name="checkall" onclick="toggleAll(this)"/></th>
    <th scope="col">Name</th>
    <th scope="col">Email</th>
  </tr>
<?php
	$n = count($contactlist);
	for ($i=0; $i<$n; $i++) {
	 	$contact = $contactlist[$i];
	 	$name = $contact->name;
	 	$email = $contact->email;
?>
  <tr>
    <td><input type="checkbox" name="emails[]" value="<?php echo htmlentities($email).':::'.htmlentities($name) ?>" /></td>
    <td><?php echo htmlentities($name, ENT_COMPAT,'UTF-8') ?></td>
    <td><?php echo htmlentities($email) ?></td>
  </tr>
<?php
	}
?>  
</table>
<input type="submit" name="sendInvite" value="Send invite" />
<?php
}
?>

<p>&nbsp;</p>
</form>
<div id="footer">Powered by <a href="http://www.octazen.com">Octazen Solutions</a> Contacts Importer. Bug or feedback? Click <a href="http://www.octazen.com/contact/">here</a>.</div>
</body>
</html>
