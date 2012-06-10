<?php
/********************************************************************************
Sample Application (CSV/LDIF Parser)

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2008 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/
include_once("../abimporter/abi.php");


$contactlist = null;
$errmsg = '';
if (isset($_REQUEST['sendInvite']) && isset($_REQUEST['emails'])) {
 
 //&& $_REQUEST['sendInvite']=='Send invite' 
 	$email = $_REQUEST['email'];
 	if (empty($email)) {
		$errmsg='Sender email address must be specified. Please try again.';
	}
	else {
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
				mail($to, $subject, $message, $headers);
				echo "Mail sent to $name ($to)<br/>\r\n";
			}
		}
	}
}
else if (isset($_REQUEST['post'])) {

	if (is_uploaded_file($_FILES['file1']['tmp_name'])) {
		//echo "File ". $_FILES['file1']['name'] ." uploaded successfully.\n";
		//echo "Displaying contents\n";
		//readfile($_FILES['file1']['tmp_name']);
		$filename = $_FILES['file1']['tmp_name'];
		if (file_exists($filename)) {
			$csv = file_get_contents($filename);
			if (!empty($csv)) {
			 	//Parse the CSV file
				$format = $_REQUEST['format'];
				if ($format=="o") {
					$res = abi_extract_outlook_csv($csv);
				}
				else if ($format=="oe") {
					$res = abi_extract_outlook_csv($csv);
				}
				else if ($format=="tb") {
					$res = abi_extract_thunderbird_csv($csv);
				}
				else if ($format=="tbldif") {
					$res = abi_extract_ldif($csv);
				}
				
				//Figure out error
				if (is_int($res)) {
					$errmsg='Bad/Unrecognized format';
				}
				else {
					$contactlist = $res;
				}				
			}
			unlink($filename);
		}
	} 
 	////////////////////////////////////////////////////////
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CSV Contacts Importer</title>
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
<h1>CSV Contacts Importer</h1>
<p>[ <a href="importer.php">I want to import from my webmail address book instead</a> ]</p>
<p><strong>Exporting the CSV files from web application </strong></p>
<table border="1" cellspacing="0" cellpadding="3">
  <tr valign="top">
    <td><strong>Outlook 2000-2003</strong></td>
    <td><ol>
      <li>Switch to Contact view</li>
    <li>Go to File menu, select &quot;Import/Export&quot;. Import and Export Wizard window appears.</li>
      <li>Select &quot;Export to a file&quot;. Click Next.</li>
    <li>Select &quot;Comma Separated Values&quot; (DOS or Windows). Click Next. </li>
    <li>Select where to save the CSV file. Click Next.</li>
    <li>Click Finish.</li>
    </ol></td>
  </tr>
  <tr valign="top">
    <td><strong>Outlook Express 6 </strong></td>
    <td><ol>
      <li>Go to File-&gt;Export-&gt;Address Book menu. Address book export tool appears. </li>
    <li>Select &quot;Text File (Comma Separated Values)&quot;. Click Export.</li>
      <li>Select where to save the CSV file. Click Next.</li>
      <li>Click Finish </li>
    </ol></td>
  </tr>
  <tr valign="top">
    <td><strong>Thunderbird CSV </strong></td>
    <td><ol>
        <li>Go to Tools-&gt;Address Book menu. Address book window appears.</li>
        <li>Go to Tools-&gt;Export menu.</li>
        <li>Select path to save file. Remember to select &quot;Comma Separated (*.CSV)&quot; under the &quot;Save as type&quot; dropdown list. Click save.</li>
    </ol></td>
  </tr>
  <tr valign="top">
    <td><strong>Thunderbird LDIF </strong></td>
    <td><ol>
      <li>Go to Tools-&gt;Address Book menu. Address book window appears.</li>
      <li>Go to Tools-&gt;Export menu.</li>
      <li>Select path to save file. LDIF is selected by default. Click save.</li>
    </ol></td>
  </tr>
</table>
<form action="" method="post" enctype="multipart/form-data" name="f1">
<p>
  <input name="post" type="hidden" id="post" value="1" />
  </p>
<table border="0" cellspacing="3" cellpadding="0">
  <tr>
    <td><div align="right"><strong>Format      </strong></div></td>
    <td><select name="format" id="format">
      <option value="o" selected="selected">Outlook 2000-2003 CSV</option>
      <option value="oe">Outlook Express 6 CSV</option>
      <option value="tb">Thunderbird CSV</option>
      <option value="tbldif">Thunderbird LDIF</option>
    </select></td>
  </tr>
  <tr>
    <td><div align="right"><strong>CSV file to upload</strong></div></td>
    <td><input name="file1" type="file" id="file1" size="50" /></td>
  </tr>
</table>
<p>
  <input type="submit" name="Submit" value="Import" />
</p>
<p><strong><font color="#FF0000" size="3"><?php echo htmlentities($errmsg) ?></font></strong>
  <?php
////////////////////////////////////////////////////////
//PRINT OUT TABLE OF RETRIEVED CONTACTS
////////////////////////////////////////////////////////
if (isset($contactlist)) {
?>
</p>
<table border="0" cellspacing="0" cellpadding="0" class="table">
  <tr>
    <th scope="col">&nbsp;</th>
    <th scope="col">Name</th>
    <th scope="col">Email</th>
  </tr>
<?php
	$n = count($contactlist);
	for ($i=0; $i<$n; $i++) {
	 	$contact = $res[$i];
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
<p>Sender email address
    <input name="email" type="text" id="email" />
</p>
<p>      <input type="submit" name="sendInvite" value="Send invite" />
    <?php
}
?>
    
</p>
<p>&nbsp;</p>
<div id="footer">Powered by <a href="http://www.octazen.com">Octazen Solutions</a> Contacts Importer. Bug or feedback? Click <a href="http://www.octazen.com/contact/">here</a>.</div>
</form>
</body>
</html>
