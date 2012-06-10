<?php

/* $Id: user_logout.php 8 2009-01-11 06:02:53Z john $ */

$page = "user_logout";
include "header.php";

$user->user_logout();

// FORWARD TO USER LOGIN PAGE
cheader("index.php");
exit();
?>