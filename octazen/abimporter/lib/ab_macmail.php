<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Mac.com (now Mobileme - me.com) contacts importer

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
//include_once(dirname(__FILE__).'/abimporter.php');
if (!defined('__ABI')) die('Please include abi.php to use this importer!');

global $_OZ_SERVICES;
$_OZ_SERVICES['medotcom'] = array('type'=>'abi', 'label'=>'MobileMe (.Mac)', 'class'=>'MeDotComImporter');

/////////////////////////////////////////////////////////////////////////////////////////
//MeDotComImporter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class MeDotComImporter extends WebRequestor {

	var $MAX_CONTACTS = 200;

	//@api
	function login($loginemail, $password)  {

		$this->enableHttp1_1Features(true);
		//$this->useHttp1_1 = true;

	 	$login = $this->getEmailParts($loginemail);
	 	$login = $login[0];
		$form = new HttpForm;

		//new?		
        $form->addField("returnURL","aHR0cDovL3d3dy5tZS5jb20vd28vV2ViT2JqZWN0cy9Eb2NrU3RhdHVzLndvYS93YS90cmFtcG9saW5lP2Rlc3RpbmF0aW9uVXJsPS9jb250YWN0cw==");
		//$form->addField("returnURL","aHR0cDovL3d3dy5tZS5jb20vd28vV2ViT2JqZWN0cy9Eb2NrU3RhdHVzLndvYS93YS90cmFtcG9saW5lP2Rlc3RpbmF0aW9uVXJsPS9tYWls");
		$form->addField("service", "DockStatus");
		$form->addField("realm", "primary-me");
		$form->addField("cancelURL", "http://www.me.com/contacts");
		$form->addField("formID", "loginForm");
		$form->addField("username", $login); // email is fine too
		$form->addField("password", $password);
		$form->addField("keepLoggedIn", "");
		$form->addField("_authtrkcde", "{#TRKCDE#}");
		$postData = $form->buildPostData();
		$html = $this->httpPost("https://auth.apple.com/authenticate", $postData);
		if (strpos($html, 'id="error"')!==false) {
		 	$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad user name or password');
		}
		
		if (strpos($this->lastUrl,'http://www.me.com')===false) {
		 	$this->close();
			return abi_set_error(_ABI_FAILED,'Unable to login');
		}
		
		return abi_set_success();
	}

	//@api
	function fetchContacts ($loginemail, $password) {

		$res = $this->login($loginemail, $password);
		if ($res!=_ABI_SUCCESS) return $res;

		$headers = array("X-Mobileme-Version: 1.0", "X-Requested-With: XMLHttpRequest", "X-Prototype-Version: 1.6.0");

		// ////////////////////////////////////////////////////////////////////////////
		// Fetch JSON contacts list
		// ////////////////////////////////////////////////////////////////////////////
		$guids = array();
		//$html = $this->httpPost("http://www.me.com/wo/WebObjects/Contacts.woa/wa/ScriptAction/loadContacts?lang=en", '');
		$html = $this->httpPost("http://www.me.com/wo/WebObjects/Contacts.woa/wa/ScriptAction/loadContacts?lang=en", '','utf-8',$headers);
		$res = oz_json_decode($html,true);
		if (is_array($res) && isset($res['crecords'])) {
			$records = null;
		 	if (isset($res['crecords'])) $records = $res['crecords'];
			if ($records==null && isset($res['records'])) $records = $res['records'];
		 	if (is_array($records)) {
			 	foreach ($records as $rec) {
			 	 	$guid = $rec['guid'];
			 	 	$type = $rec['type'];
			 	 	if ('Contact'==$type) {
			 	 	 	$guids[]=$guid;
					}
				}
			}
		}


		// ////////////////////////////////////////////////////////////////////////////
		// Perform VCard export
		// ////////////////////////////////////////////////////////////////////////////
		$form = new HttpForm;
		$guidstr = join('","',$guids);
		if (empty($guidstr)) return array();	//No results
		$guidstr = '["'.$guidstr.'"]';
        $form->addField("guid_array",$guidstr);
		$postData = $form->buildPostArray();
		$iscsecs = $this->cookiejar->getCookieValues('http://www.me.com','isc-www.me.com');
		if (empty($iscsecs) || count($iscsecs)==0) {
		 	$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find security token');
		}
		$vcard = $this->httpPost("/wo/WebObjects/Contacts.woa/wa/ScriptAction/exportVCards?iscsec=".$iscsecs[0], $postData);

		//Parse VCard
		$cl = array();
	 	$tokenizer = new OzVCardTokenizer($vcard);
	 	$in_vcard = false;
	 	
	 	$cname = '';
	 	$cemails = array();
		while (true) {
			$field = $tokenizer->next();
			if ($field==null) break;
			
			if ($in_vcard) {
				$name = $field->name;
				if ('END'==$name && "VCARD"==$field->getStringValue()) {
				 	//Flush
				 	if (count($cemails)>0) {
				 	 	foreach ($cemails as $email) {
							$cl[] = new Contact($cname,$email);
						}
					}
					$in_vcard = false;
				}
				else if ('FN'==$name) {
				 	$cname = $field->getStringValue();
				}
				else if ('EMAIL'==$name) {
				 	$e = trim($field->getStringValue());
				 	if (abi_valid_email($e)) $cemails[]=$e;
				}
			}
			else {
				$name = $field->name;
				if ('BEGIN'==$name && "VCARD"==$field->getStringValue()) {
					$in_vcard = true;
					$cname = '';
					$cemails = array();
				}
			}
		}
		return $cl;
		
/*

		// ////////////////////////////////////////////////////////////////////////////
		// Fetch each contact details
		// ////////////////////////////////////////////////////////////////////////////
		$al = array();	
		
		$n = count($guids);
		for ($cc=0; $cc<$this->MAX_CONTACTS && $cc<$n; $cc++) {
			$g = $guids[$cc];
			//$form = new HttpForm;
			//$form->addField("guid", $g);
			//$postData = $form->buildPostData();
			//$html = $this->httpPost("http://www.me.com/wo/WebObjects/Contacts.woa/wa/ScriptAction/refreshContactDetails?lang=en", "guid=$g");
			$html = $this->httpPost("http://www.me.com/wo/WebObjects/Contacts.woa/wa/ScriptAction/refreshContactDetails?lang=en", "guid=$g", 'utf-8', $headers);
			
			$emails = array();
			$contacts = array();
			$res = oz_json_decode($html,true);
			$records = null;
		 	if (isset($res['crecords'])) $records = $res['crecords'];
			if ($records==null && isset($res['records'])) $records = $res['records'];
			if (is_array($res) && $records!=null) {
			 	//$records = $res['crecords'];
			 	if (is_array($records)) {
				 	foreach ($records as $rec) {
				 	 	$guid = $rec['guid'];
				 	 	$type = $rec['type'];
				 	 	$value = isset($rec['associationValue']) ? $rec['associationValue'] : null;
				 	 	if ('Contact'==$type) {
							$contacts[] = $rec;
						}
						else if ('Email'==$type) {
							$emails[$guid] = $value;
						}
						//Others...
					}
				}
			}
			
			foreach ($contacts as $c) {
			 	if (isset($c['emailAddresses'])) {
					$emailList = $c['emailAddresses'];
					if (is_array($emailList)) {
					 
						$fname = isset($c['firstName']) ? $c['firstName'] : '';
						$lname = isset($c['lastName']) ? $c['lastName'] : '';
						$name = trim(oz_reduce_whitespace($fname.' '.$lname));
						
					 	foreach ($emailList as $emailGuid) {
					 	 	if (isset($emails[$emailGuid])) {
					 	 		$email = $emails[$emailGuid];
					 	 		//abi_valid_email ??
					 	 		$al[] = new Contact($name,$email);
							}
						}
					}
				}
			}
		}
	
	 	$this->close();
		return $al;
*/		
	}
}

//For legacy support
//@api
class MacMailImporter extends MeDotComImporter {}

// Mac.com
global $_DOMAIN_IMPORTERS;
$_DOMAIN_IMPORTERS["mac.com"]='MeDotComImporter';
$_DOMAIN_IMPORTERS["me.com"]='MeDotComImporter';

?>
