<?php
$page = "twitter_oauth_dummy";
include "header.php";


// CONFIRM OAUTH TOKEN/ REQUEST
$epiAuth = new EpiOAuth($SEP_Twitter_Config['TWITTER_CONSUMER_KEY'], $SEP_Twitter_Config['TWITTER_CONSUMER_SECRET']);
$epiAuth->setToken($_GET['oauth_token']);
$token = $epiAuth->getAccessToken();
$epiAuth->setToken($token->oauth_token, $token->oauth_token_secret);
$SEP_TwitterUser->set_oauth_tokens($token->oauth_token, $token->oauth_token_secret);


// Redirect to twitter settings page
header("Location: user_twitter_settings.php");
exit;


include "footer.php";
?>