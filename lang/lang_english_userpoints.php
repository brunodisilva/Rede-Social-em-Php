<?


// SET GENERAL VARIABLES, AVAILABLE ON EVERY PAGE
$header_userpoints[1] = "Points";
$header_userpoints[2] = "Points Shop";
$header_userpoints[3] = "Top Users";


// ASSIGN ALL SMARTY GENERAL VARIABLES
reset($header_userpoints);
while(list($key, $val) = each($header_userpoints)) {
  $smarty->assign("header_userpoints".$key, $val);
}

$semods_installer_action[1] = "Posting a comment on Points Earning Offer.";
$semods_installer_action[2] = "<a href='profile.php?user=%1$s'>%2$s</a> posted a comment on the offer <a href='user_points_offers_item.php?item_id=%3$s'>%4$s</a>:<div class='recentaction_div'>%5$s</div>";

$semods_installer_action[3] = "Posting a comment on Points Shop Item.";
$semods_installer_action[4] = "<a href='profile.php?user=%1$s'>%2$s</a> posted a comment on the points shop item <a href='user_points_shop_item.php?shopitem_id=%3$s'>%4$s</a>:<div class='recentaction_div'>%5$s</div>";


// FUNCTION VARIABLES

$functions_userpoints[1] = "You don't have enough points to post classified.";
$functions_userpoints[2] = "You don't have enough points to create an event.";
$functions_userpoints[3] = "You don't have enough points to create a group.";
$functions_userpoints[4] = "You don't have enough points to create a poll.";

$functions_userpoints[10] = "Thank you for your order!  You transaction will be approved after we receive confirmation from payment company (usually almost instantly).";
$functions_userpoints[11] = "You order was cancelled.";

$functions_userpoints[12] = "Completed";
$functions_userpoints[13] = "Pending";
$functions_userpoints[14] = "Cancelled";

$functions_userpoints[20] = "Error. Please contact administrator";
$functions_userpoints[21] = "Unsupported item.";
$functions_userpoints[22] = "You don't have enough points.";

$functions_userpoints[30] = "Visiting Affiliate";

$functions_userpoints[40] = "Voting for a";
$functions_userpoints[41] = "This poll doesn't exist.";
$functions_userpoints[42] = "poll";

$functions_userpoints[50] = "Purchasing points";
$functions_userpoints[51] = "Please contact webmaster - Payment module not installed.";

$functions_userpoints[60] = "Level Upgrade";
$functions_userpoints[61] = "You can't upgrade from this level.";
$functions_userpoints[62] = "Your level was upgraded!";

$functions_userpoints[70] = "Promoting my profile";
$functions_userpoints[71] = "Invalid template, please contact administrator.";
$functions_userpoints[72] = "Invalid classified";
$functions_userpoints[73] = "Promoting my";
$functions_userpoints[74] = "classified";
$functions_userpoints[75] = "Invalid event";
$functions_userpoints[76] = "event";
$functions_userpoints[77] = "Invalid group";
$functions_userpoints[78] = "group";
$functions_userpoints[79] = "Invalid poll";
$functions_userpoints[80] = "poll";
$functions_userpoints[81] = "Your promotion has been accepted";
$functions_userpoints[82] = " and will start immediately.";
$functions_userpoints[83] = " and will be active starting from tomorrow.";
$functions_userpoints[84] = "You don't have any classifieds.";
$functions_userpoints[85] = "You don't have any events.";
$functions_userpoints[86] = "You don't have any groups.";
$functions_userpoints[87] = "You don't have any polls.";

$functions_userpoints[100] = "You don't have enough points.";
$functions_userpoints[101] = "Please enter amount greater than zero.";
$functions_userpoints[102] = "Recipient doesn't exist or can't accept points.";
$functions_userpoints[103] = "Points were transferred successfully.";
$functions_userpoints[104] = "You are not allowed to transfer points.";
$functions_userpoints[105] = "You can only transfer %d points.";
$functions_userpoints[106] = "You have used up your transfer quota.";
$functions_userpoints[107] = "Points transfer to";
$functions_userpoints[108] = "Points transfer from";

$functions_userpoints[110] = "Generic";



$actions_desc["invite"]	= "Invite friends";
$actions_desc["refer"] 	= "Referring friends (actual signup)";


// SET LANGUAGE PAGE VARIABLES
switch ($page) {

  case "admin_userpoints_userstats":
	$admin_userpoints_userstats[1] = "Activity Statistics:";
	$admin_userpoints_userstats[2] = "Use this page to monitor activity of a user on your network.";
	$admin_userpoints_userstats[7] = "Week of";
	$admin_userpoints_userstats[8] = " (";
	$admin_userpoints_userstats[9] = ")";
	$admin_userpoints_userstats[19] = "Period:";
	$admin_userpoints_userstats[20] = "This Week (Daily)";
	$admin_userpoints_userstats[21] = "This Month (Daily)";
	$admin_userpoints_userstats[22] = "This Year (Monthly)";
	$admin_userpoints_userstats[23] = "Refresh";
	$admin_userpoints_userstats[24] = "Imported contacts";
	$admin_userpoints_userstats[25] = "Invited contacts";
	$admin_userpoints_userstats[26] = "Points Earned";
	$admin_userpoints_userstats[27] = "Points Spent";
	$admin_userpoints_userstats[34] = "User";
	$admin_userpoints_userstats[35] = "No data.";

	$admin_userpoints_userstats[41] = "Last Period";
	$admin_userpoints_userstats[42] = "Next Period";
	break;



  case "admin_userpoints_usertransactions":
	$admin_userpoints_usertransactions[1] = "Transactions for user:";
	$admin_userpoints_usertransactions[2] = "View users' points transactions.";
	$admin_userpoints_usertransactions[3] = "Username";
	$admin_userpoints_usertransactions[4] = "Transaction status:";
	$admin_userpoints_usertransactions[5] = "No transaction found.";
	$admin_userpoints_usertransactions[6] = "Description";

	$admin_userpoints_usertransactions[9] = "Yes";
	$admin_userpoints_usertransactions[10] = "No";

	$admin_userpoints_usertransactions[14] = "Filter";
	$admin_userpoints_usertransactions[15] = "ID";
	$admin_userpoints_usertransactions[16] = "Transactions Found";
	$admin_userpoints_usertransactions[17] = "Page:";

	$admin_userpoints_usertransactions[18] = "User";
	$admin_userpoints_usertransactions[19] = "Date";
	$admin_userpoints_usertransactions[20] = "Description";
	$admin_userpoints_usertransactions[21] = "Status";
	$admin_userpoints_usertransactions[22] = "Amount";

	$admin_userpoints_usertransactions[23] = "TID";


	$admin_userpoints_usertransactions[24] = "User Level";
	$admin_userpoints_usertransactions[25] = "Subnetwork";
	$admin_userpoints_usertransactions[26] = "Default";

	$admin_userpoints_usertransactions[27] = "confirm";
	$admin_userpoints_usertransactions[28] = "cancel";

	break;



  case "admin_userpoints":
	$admin_userpoints[1] = "General Activity Points Settings";
	$admin_userpoints[2] = "This page contains general Activity Points settings.";
	$admin_userpoints[3] = "Your changes have been saved.";

	$admin_userpoints[8] = "Enable Top Users?";
	$admin_userpoints[9] = "This will show a page of top users ranked by total amount of accumulated points (regardless of their current points \"balance\") and also show user's rank on profile and user homepage.";
	$admin_userpoints[10] = "Yes, enable Top Users.";
	$admin_userpoints[11] = "No, disable Top Users.";

	$admin_userpoints[13] = "Save Changes";

	$admin_userpoints[14] = "Enable Offers (Earn Points)?";
	$admin_userpoints[15] = "Do you want to allow users to view \"Earn Points\" page and participate in offers for gaining points?";
	$admin_userpoints[16] = "Yes, enable Offers.";
	$admin_userpoints[17] = "No, disable Offers.";

	$admin_userpoints[18] = "Enable Points Shop (Spend Points)?";
	$admin_userpoints[19] = "Do you want to allow users to view \"Spend Points\" page and purchase items?";
	$admin_userpoints[20] = "Yes, enable Points Shop.";
	$admin_userpoints[21] = "No, disable Points Shop.";

	$admin_userpoints[22] = "Enable Activity Statistics?";
	$admin_userpoints[23] = "Do you want to gather daily points activity statistics per user?";
	$admin_userpoints[24] = "Yes, enable Activity Statistics.";
	$admin_userpoints[25] = "No, disable Activity Statistics.";

    break;


  case "admin_userpoints_pointranks":
	$admin_userpoints_pointranks[1] = "Points Rankings";
	$admin_userpoints_pointranks[2] = "This page allows setting rankings based on total earned points count.";
	$admin_userpoints_pointranks[3] = "Your changes have been saved.";

	$admin_userpoints_pointranks[4] = "Enable Points Ranking?";
	$admin_userpoints_pointranks[5] = "Control if you want to show rank calculated from user's total earned points till date based on table below on his profile and user home page.";
	$admin_userpoints_pointranks[6] = "Yes, enable points ranking.";
	$admin_userpoints_pointranks[7] = "No, disable points ranking.";

	$admin_userpoints_pointranks[8] = "Rankings";
	$admin_userpoints_pointranks[9] = "Rankings";
	$admin_userpoints_pointranks[10] = "Points from";
	$admin_userpoints_pointranks[11] = "Rank Title";
	$admin_userpoints_pointranks[12] = "+ Add more";

	$admin_userpoints_pointranks[13] = "Save Changes";

    break;


  case "admin_userpoints_charging":
	$admin_userpoints_charging[1] = "Charging for preset actions.";
	$admin_userpoints_charging[2] = "This page allows setting costs for posting / creating new events, classifieds, groups and polls. <br>If you enable charging, DO NOT FORGET to <strong>zero activity points</strong> assigned for same action, e.g. creating group.";
	$admin_userpoints_charging[3] = "Your changes have been saved.";

	$admin_userpoints_charging[4] = "Classifieds";
	$admin_userpoints_charging[5] = "Select whether or not you want to charge for posting a new classified.";
	$admin_userpoints_charging[6] = "Yes, charge for posting a classified";
	$admin_userpoints_charging[7] = "No, do not charge for posting a classified ";
	$admin_userpoints_charging[8] = "If you have selected \"Yes\" above, please specify points cost for posting a classified.";
	$admin_userpoints_charging[9] = "points";

	$admin_userpoints_charging[10] = "Groups";
	$admin_userpoints_charging[11] = "Select whether or not you want to charge for posting a new group.";
	$admin_userpoints_charging[12] = "Yes, charge for posting a group";
	$admin_userpoints_charging[13] = "No, do not charge for posting a group";
	$admin_userpoints_charging[14] = "If you have selected \"Yes\" above, please specify points cost for posting a group.";

	$admin_userpoints_charging[15] = "Polls";
	$admin_userpoints_charging[16] = "Select whether or not you want to charge for posting a new poll.";
	$admin_userpoints_charging[17] = "Yes, charge for posting a poll";
	$admin_userpoints_charging[18] = "No, do not charge for posting a poll";
	$admin_userpoints_charging[19] = "If you have selected \"Yes\" above, please specify points cost for posting a poll.";

	$admin_userpoints_charging[20] = "Events";
	$admin_userpoints_charging[21] = "Select whether or not you want to charge for posting a new event.";
	$admin_userpoints_charging[22] = "Yes, charge for posting an event";
	$admin_userpoints_charging[23] = "No, do not charge for posting a event";
	$admin_userpoints_charging[24] = "If you have selected \"Yes\" above, please specify points cost for posting an event.";


	$admin_userpoints_charging[25] = "Save Changes";

    break;


  case "admin_userpoints_offers":
	$admin_userpoints_offers[1] = "View Offers";
	$admin_userpoints_offers[2] = "This page lists all of the offers available for users. For more information about a specific offer, click on the \"edit\" link in its row. Use the filter fields to find specific offer based on your criteria. To view all offers, leave all the filter fields blank. ";
    $admin_userpoints_offers[3] = "Title";
    $admin_userpoints_offers[4] = "Type";
    $admin_userpoints_offers[5] = "Levels";
    $admin_userpoints_offers[6] = "Subnets";

    $admin_userpoints_offers[7] = "Views";
    $admin_userpoints_offers[8] = "Comments";

    $admin_userpoints_offers[9] = "Yes";
    $admin_userpoints_offers[10] = "No";
    $admin_userpoints_offers[11] = "edit";
    $admin_userpoints_offers[12] = "disable";

    $admin_userpoints_offers[13] = "Add date";

    $admin_userpoints_offers[14] = "Filter";

    $admin_userpoints_offers[15] = "delete";

	$admin_userpoints_offers[16] = "Offers Found";
	$admin_userpoints_offers[17] = "Page:";

	$admin_userpoints_offers[18] = "Delete User";
	$admin_userpoints_offers[19] = "Are you sure you want to delete this offer?";
	$admin_userpoints_offers[20] = "Cancel";
	$admin_userpoints_offers[21] = "No offers were found.";

	$admin_userpoints_offers[22] = "enable";

	$admin_userpoints_offers[23] = "Add New Offer";
	$admin_userpoints_offers[24] = "User Level";
	$admin_userpoints_offers[25] = "Subnetwork";
	$admin_userpoints_offers[26] = "Default";
    $admin_userpoints_offers[27] = "Disable Selected";

    $admin_userpoints_offers[28] = "Enabled";
    $admin_userpoints_offers[29] = "Options";

    $admin_userpoints_offers[30] = "Points Gain";

    $admin_userpoints_offers[31] = "Choose type:";

    $admin_userpoints_offers[32] = "Acts";


    break;


  case "admin_userpoints_shop":
	$admin_userpoints_shop[1] = "View Shop Items";
	$admin_userpoints_shop[2] = "This page lists all of the items available for users to purchase. For more information about a specific item, click on the \"edit\" link in its row. Use the filter fields to find specific item based on your criteria. To view all items, leave all the filter fields blank. ";
    $admin_userpoints_shop[3] = "Title";
    $admin_userpoints_shop[4] = "Type";
    $admin_userpoints_shop[5] = "Levels";
    $admin_userpoints_shop[6] = "Subnets";

    $admin_userpoints_shop[7] = "Views";
    $admin_userpoints_shop[8] = "Comments";



    $admin_userpoints_shop[9] = "Yes";
    $admin_userpoints_shop[10] = "No";
    $admin_userpoints_shop[11] = "edit";
    $admin_userpoints_shop[12] = "disable";

    $admin_userpoints_shop[13] = "Add date";

    $admin_userpoints_shop[14] = "Filter";

    $admin_userpoints_shop[15] = "delete";

	$admin_userpoints_shop[16] = "Items Found";
	$admin_userpoints_shop[17] = "Page:";

	$admin_userpoints_shop[18] = "Delete User";
	$admin_userpoints_shop[19] = "Are you sure you want to delete this offer?";
	$admin_userpoints_shop[20] = "Cancel";
	$admin_userpoints_shop[21] = "No offers were found.";

	$admin_userpoints_shop[22] = "enable";

	$admin_userpoints_shop[23] = "Add New Item";
	$admin_userpoints_shop[24] = "User Level";
	$admin_userpoints_shop[25] = "Subnetwork";
	$admin_userpoints_shop[26] = "Default";
    $admin_userpoints_shop[27] = "Disable Selected";

    $admin_userpoints_shop[28] = "Enabled";
    $admin_userpoints_shop[29] = "Options";

    $admin_userpoints_shop[30] = "Cost";
    $admin_userpoints_shop[31] = "Acts";

    $admin_userpoints_shop[32] = "Choose type:";


    break;


  case "admin_userpoints_shop_generic":
    $admin_userpoints_shop_generic[1] = "Add Shop Item - Generic";
    $admin_userpoints_shop_generic[2] = "Add Shop Item - Generic";
    $admin_userpoints_shop_generic[3] = "Title:";
    $admin_userpoints_shop_generic[4] = "Description:";
    $admin_userpoints_shop_generic[5] = "Redirect URL:";
    $admin_userpoints_shop_generic[6] = "Show in transactions?";
    $admin_userpoints_shop_generic[7] = "Transaction state:";
    $admin_userpoints_shop_generic[8] = "Cost:";

    $admin_userpoints_shop_generic[9] = "Save offer";
    $admin_userpoints_shop_generic[10] = "Cancel";
    $admin_userpoints_shop_generic[11] = "Enabled?";
    $admin_userpoints_shop_generic[12] = "Enabled";
    $admin_userpoints_shop_generic[13] = "Disabled";

    $admin_userpoints_shop_generic[14] = "User levels";
    $admin_userpoints_shop_generic[15] = "Subnets";

    $admin_userpoints_shop_generic[16] = "Tags:";
    $admin_userpoints_shop_generic[17] = "Allow comments?";

    $admin_userpoints_shop_generic[18] = "Completed";
    $admin_userpoints_shop_generic[19] = "Pending";

    $admin_userpoints_shop_generic[24] = "Yes";
    $admin_userpoints_shop_generic[25] = "No";
    $admin_userpoints_shop_generic[26] = "Changes saved.";

    $admin_userpoints_shop_generic[27] = "(signup default)";
    $admin_userpoints_shop_generic[28] = "Levels:";
    $admin_userpoints_shop_generic[29] = "Subnets:";
    $admin_userpoints_shop_generic[30] = "Select options";
    $admin_userpoints_shop_generic[31] = "Select all";

    $admin_userpoints_shop_generic[40] = "Edit offer";
    $admin_userpoints_shop_generic[41] = "Edit photo";
    $admin_userpoints_shop_generic[42] = "Edit comments";
    $admin_userpoints_shop_generic[43] = "Back to Listings";

    $admin_userpoints_shop_generic[45] = "Please enter title.";
    break;


  case "admin_userpoints_offers_generic":
    $admin_userpoints_offers_generic[1] = "Add Offer - Generic";
    $admin_userpoints_offers_generic[2] = "Add Offer - Generic";
    $admin_userpoints_offers_generic[3] = "Title:";
    $admin_userpoints_offers_generic[4] = "Description:";
    $admin_userpoints_offers_generic[5] = "Redirect URL:";
    $admin_userpoints_offers_generic[6] = "Show in transactions?";
    $admin_userpoints_offers_generic[7] = "Transaction state:";
    $admin_userpoints_offers_generic[8] = "Points:";

    $admin_userpoints_offers_generic[9] = "Save offer";
    $admin_userpoints_offers_generic[10] = "Cancel";
    $admin_userpoints_offers_generic[11] = "Enabled?";
    $admin_userpoints_offers_generic[12] = "Enabled";
    $admin_userpoints_offers_generic[13] = "Disabled";

    $admin_userpoints_offers_generic[14] = "User levels";
    $admin_userpoints_offers_generic[15] = "Subnets";

    $admin_userpoints_offers_generic[16] = "Tags:";
    $admin_userpoints_offers_generic[17] = "Allow comments?";

    $admin_userpoints_offers_generic[18] = "Completed";
    $admin_userpoints_offers_generic[19] = "Pending";

    $admin_userpoints_offers_generic[24] = "Yes";
    $admin_userpoints_offers_generic[25] = "No";
    $admin_userpoints_offers_generic[26] = "Changes saved.";

    $admin_userpoints_offers_generic[27] = "(signup default)";
    $admin_userpoints_offers_generic[28] = "Levels:";
    $admin_userpoints_offers_generic[29] = "Subnets:";
    $admin_userpoints_offers_generic[30] = "Select options";
    $admin_userpoints_offers_generic[31] = "Select all";

    $admin_userpoints_offers_generic[40] = "Edit offer";
    $admin_userpoints_offers_generic[41] = "Edit photo";
    $admin_userpoints_offers_generic[42] = "Edit comments";
    $admin_userpoints_offers_generic[43] = "Back to Listings";

    $admin_userpoints_offers_generic[45] = "Please enter title.";
    break;






  case "admin_userpoints_shop_levelupgrade":
    $admin_userpoints_shop_levelupgrade[1] = "Add Offer - Level Upgrade";
    $admin_userpoints_shop_levelupgrade[2] = "Add Offer - Level Upgrade";
    $admin_userpoints_shop_levelupgrade[3] = "Title:";
    $admin_userpoints_shop_levelupgrade[4] = "Description:";
    $admin_userpoints_shop_levelupgrade[5] = "Level from:";
    $admin_userpoints_shop_levelupgrade[6] = "Level to:";
    $admin_userpoints_shop_levelupgrade[7] = "Any";
    $admin_userpoints_shop_levelupgrade[8] = "Cost";

    $admin_userpoints_shop_levelupgrade[9] = "Save offer";
    $admin_userpoints_shop_levelupgrade[10] = "Cancel";
    $admin_userpoints_shop_levelupgrade[11] = "Enabled?";
    $admin_userpoints_shop_levelupgrade[12] = "Enabled";
    $admin_userpoints_shop_levelupgrade[13] = "Disabled";

    $admin_userpoints_shop_levelupgrade[14] = "User levels";
    $admin_userpoints_shop_levelupgrade[15] = "Subnets";

    $admin_userpoints_shop_levelupgrade[16] = "Tags:";
    $admin_userpoints_shop_levelupgrade[17] = "Allow comments?";

    $admin_userpoints_shop_levelupgrade[24] = "Yes";
    $admin_userpoints_shop_levelupgrade[25] = "No";
    $admin_userpoints_shop_levelupgrade[26] = "Changes saved.";

    $admin_userpoints_shop_levelupgrade[27] = "(signup default)";
    $admin_userpoints_shop_levelupgrade[28] = "Levels:";
    $admin_userpoints_shop_levelupgrade[29] = "Subnets:";
    $admin_userpoints_shop_levelupgrade[30] = "Select options";
    $admin_userpoints_shop_levelupgrade[31] = "Select all";

    $admin_userpoints_shop_levelupgrade[40] = "Edit offer";
    $admin_userpoints_shop_levelupgrade[41] = "Edit photo";
    $admin_userpoints_shop_levelupgrade[42] = "Edit comments";
    $admin_userpoints_shop_levelupgrade[43] = "Back to Listings";

    $admin_userpoints_shop_levelupgrade[45] = "Please enter title.";
    break;


  case "admin_userpoints_shop_promote":
    $admin_userpoints_shop_promote[1] = "Add Offer - Promotion";
    $admin_userpoints_shop_promote[2] = "Promotion allows your users to create ad campaigns on their content - profile, classified, event, group or poll.";
    $admin_userpoints_shop_promote[3] = "Title:";
    $admin_userpoints_shop_promote[4] = "Description:";

    $admin_userpoints_shop_promote[5] = "Level from:";
    $admin_userpoints_shop_promote[6] = "Level to:";
    $admin_userpoints_shop_promote[7] = "Any";

    $admin_userpoints_shop_promote[8] = "Cost";

    $admin_userpoints_shop_promote[9] = "Save offer";
    $admin_userpoints_shop_promote[10] = "Cancel";
    $admin_userpoints_shop_promote[11] = "Enabled?";
    $admin_userpoints_shop_promote[12] = "Enabled";
    $admin_userpoints_shop_promote[13] = "Disabled";

    $admin_userpoints_shop_promote[14] = "User levels";
    $admin_userpoints_shop_promote[15] = "Subnets";

    $admin_userpoints_shop_promote[16] = "Tags:";
    $admin_userpoints_shop_promote[17] = "Allow comments?";

    $admin_userpoints_shop_promote[18] = "(reset html)";

    $admin_userpoints_shop_promote[20] = "Promotion template:";
    $admin_userpoints_shop_promote[21] = "Promotion type:";
    $admin_userpoints_shop_promote[22] = "Ad html";
    $admin_userpoints_shop_promote[23] = "Require approval?";
    $admin_userpoints_shop_promote[24] = "Yes";
    $admin_userpoints_shop_promote[25] = "No";
    $admin_userpoints_shop_promote[26] = "Start";
    $admin_userpoints_shop_promote[27] = "Immediately";
    $admin_userpoints_shop_promote[28] = "Delay for one day";
    $admin_userpoints_shop_promote[29] = "Duration:";
    $admin_userpoints_shop_promote[30] = "day(s)";

    $admin_userpoints_shop_promote[31] = "(signup default)";
    $admin_userpoints_shop_promote[32] = "Levels:";
    $admin_userpoints_shop_promote[33] = "Subnets:";
    $admin_userpoints_shop_promote[34] = "Select options";
    $admin_userpoints_shop_promote[35] = "Select all";

    $admin_userpoints_shop_promote[39] = "Changes saved.";
    $admin_userpoints_shop_promote[40] = "Edit offer";
    $admin_userpoints_shop_promote[41] = "Edit photo";
    $admin_userpoints_shop_promote[42] = "Edit comments";
    $admin_userpoints_shop_promote[43] = "Back to Listings";

    $admin_userpoints_shop_promote[45] = "Please enter title.";
    $admin_userpoints_shop_promote[46] = "Please choose a promotion type.";

    $admin_userpoints_shop_promote[101] = "You don't have Classifieds plugin installed.";
    $admin_userpoints_shop_promote[102] = "You don't have Events plugin installed.";
    $admin_userpoints_shop_promote[103] = "You don't have Groups plugin installed.";
    $admin_userpoints_shop_promote[104] = "You don't have Polls plugin installed.";
    break;







  case "admin_userpoints_offers_votepoll":
    $admin_userpoints_offers_votepoll[1] = "Add Offer - Vote Poll";
    $admin_userpoints_offers_votepoll[2] = "Here you can pitch a specific poll for your users.";
    $admin_userpoints_offers_votepoll[3] = "Title:";
    $admin_userpoints_offers_votepoll[4] = "Description:";

    $admin_userpoints_offers_votepoll[5] = "Poll ID:";

    $admin_userpoints_offers_votepoll[7] = "Any";
    $admin_userpoints_offers_votepoll[8] = "Points:";

    $admin_userpoints_offers_votepoll[9] = "Save offer";
    $admin_userpoints_offers_votepoll[10] = "Cancel";
    $admin_userpoints_offers_votepoll[11] = "Enabled?";
    $admin_userpoints_offers_votepoll[12] = "Enabled";
    $admin_userpoints_offers_votepoll[13] = "Disabled";

    $admin_userpoints_offers_votepoll[14] = "User levels";
    $admin_userpoints_offers_votepoll[15] = "Subnets";

    $admin_userpoints_offers_votepoll[16] = "Tags:";
    $admin_userpoints_offers_votepoll[17] = "Allow comments?";

    $admin_userpoints_offers_votepoll[18] = "Points added:";
    $admin_userpoints_offers_votepoll[19] = "Immediately";
    $admin_userpoints_offers_votepoll[20] = "Require action";

    $admin_userpoints_offers_votepoll[21] = "This poll doesn't exist.";

    $admin_userpoints_offers_votepoll[24] = "Yes";
    $admin_userpoints_offers_votepoll[25] = "No";
    $admin_userpoints_offers_votepoll[26] = "Changes saved.";

    $admin_userpoints_offers_votepoll[27] = "(signup default)";
    $admin_userpoints_offers_votepoll[28] = "Levels:";
    $admin_userpoints_offers_votepoll[29] = "Subnets:";
    $admin_userpoints_offers_votepoll[30] = "Select options";
    $admin_userpoints_offers_votepoll[31] = "Select all";

    $admin_userpoints_offers_votepoll[32] = "You don't have polls plugin installed!";

    $admin_userpoints_offers_votepoll[40] = "Edit offer";
    $admin_userpoints_offers_votepoll[41] = "Edit photo";
    $admin_userpoints_offers_votepoll[42] = "Edit comments";
    $admin_userpoints_offers_votepoll[43] = "Back to Listings";

    $admin_userpoints_offers_votepoll[45] = "Please enter title.";

    break;


  case "admin_userpoints_offers_affiliate":
    $admin_userpoints_offers_affiliate[1] = "Add Offer - Affiliate";
    $admin_userpoints_offers_affiliate[2] = "This type of offer allows you to redirect a user to your affiliate, adding customer parameters such as user id, username or transaction id.";
    $admin_userpoints_offers_affiliate[3] = "Title:";
    $admin_userpoints_offers_affiliate[4] = "Description:";

    $admin_userpoints_offers_affiliate[5] = "Affiliate URL:";

    $admin_userpoints_offers_affiliate[7] = "Any";
    $admin_userpoints_offers_affiliate[8] = "Points:";

    $admin_userpoints_offers_affiliate[9] = "Save offer";
    $admin_userpoints_offers_affiliate[10] = "Cancel";
    $admin_userpoints_offers_affiliate[11] = "Enabled?";
    $admin_userpoints_offers_affiliate[12] = "Enabled";
    $admin_userpoints_offers_affiliate[13] = "Disabled";

    $admin_userpoints_offers_affiliate[14] = "User levels";
    $admin_userpoints_offers_affiliate[15] = "Subnets";

    $admin_userpoints_offers_affiliate[16] = "Tags:";
    $admin_userpoints_offers_affiliate[17] = "Allow comments?";

    $admin_userpoints_offers_affiliate[18] = "Points added:";
    $admin_userpoints_offers_affiliate[19] = "Immediately";
    $admin_userpoints_offers_affiliate[20] = "Require action";

    $admin_userpoints_offers_affiliate[21] = "This poll doesn't exist.";

    $admin_userpoints_offers_affiliate[24] = "Yes";
    $admin_userpoints_offers_affiliate[25] = "No";

    $admin_userpoints_offers_affiliate[26] = "Changes saved.";

    $admin_userpoints_offers_affiliate[27] = "(signup default)";
    $admin_userpoints_offers_affiliate[28] = "Levels:";
    $admin_userpoints_offers_affiliate[29] = "Subnets:";
    $admin_userpoints_offers_affiliate[30] = "Select options";
    $admin_userpoints_offers_affiliate[31] = "Select all";

    $admin_userpoints_offers_affiliate[32] = "(Available parameters: [userid], [username], [transactionid])";

    $admin_userpoints_offers_affiliate[40] = "Edit offer";
    $admin_userpoints_offers_affiliate[41] = "Edit photo";
    $admin_userpoints_offers_affiliate[42] = "Edit comments";
    $admin_userpoints_offers_affiliate[43] = "Back to Listings";

    $admin_userpoints_offers_affiliate[45] = "Please enter title.";


    break;


  case "admin_userpoints_offers_purchase":
    $admin_userpoints_offers_purchase[1] = "Add Offer - Direct purchase";
    $admin_userpoints_offers_purchase[2] = "Direct purchasing allows your members to exchange real money for points.";
    $admin_userpoints_offers_purchase[3] = "Title:";
    $admin_userpoints_offers_purchase[4] = "Description:";

    $admin_userpoints_offers_purchase[5] = "Payment Gateways:";

    $admin_userpoints_offers_purchase[7] = "Any";
    $admin_userpoints_offers_purchase[8] = "Points:";

    $admin_userpoints_offers_purchase[9] = "Save offer";
    $admin_userpoints_offers_purchase[10] = "Cancel";
    $admin_userpoints_offers_purchase[11] = "Enabled?";
    $admin_userpoints_offers_purchase[12] = "Enabled";
    $admin_userpoints_offers_purchase[13] = "Disabled";

    $admin_userpoints_offers_purchase[14] = "User levels";
    $admin_userpoints_offers_purchase[15] = "Subnets";

    $admin_userpoints_offers_purchase[16] = "Tags:";
    $admin_userpoints_offers_purchase[17] = "Allow comments?";

    $admin_userpoints_offers_purchase[18] = "Points added:";
    $admin_userpoints_offers_purchase[19] = "Immediately";
    $admin_userpoints_offers_purchase[20] = "Require action";

    $admin_userpoints_offers_purchase[21] = "This poll doesn't exist.";

    $admin_userpoints_offers_purchase[24] = "Yes";
    $admin_userpoints_offers_purchase[25] = "No";

    $admin_userpoints_offers_purchase[26] = "Changes saved.";
    $admin_userpoints_offers_purchase[27] = "(signup default)";
    $admin_userpoints_offers_purchase[28] = "Levels:";
    $admin_userpoints_offers_purchase[29] = "Subnets:";
    $admin_userpoints_offers_purchase[30] = "Select options";
    $admin_userpoints_offers_purchase[31] = "Select all";

    $admin_userpoints_offers_purchase[40] = "Edit offer";
    $admin_userpoints_offers_purchase[41] = "Edit photo";
    $admin_userpoints_offers_purchase[42] = "Edit comments";
    $admin_userpoints_offers_purchase[43] = "Back to Listings";

    $admin_userpoints_offers_purchase[45] = "Please enter title.";

    break;



  case "admin_userpoints_offerphoto":
    $admin_userpoints_offerphoto[1] = "Edit offer photo";
    $admin_userpoints_offerphoto[2] = "Edit offer photo";

    $admin_userpoints_offerphoto[3] = "Current photo:";
    $admin_userpoints_offerphoto[4] = "Replace photo with:";
    $admin_userpoints_offerphoto[5] = "Upload";
    $admin_userpoints_offerphoto[6] = "Cancel";

    $admin_userpoints_offerphoto[40] = "Edit offer";
    $admin_userpoints_offerphoto[41] = "Edit photo";
    $admin_userpoints_offerphoto[42] = "Edit comments";
    $admin_userpoints_offerphoto[43] = "Back to Listings";


  break;


  case "admin_userpoints_shopphoto":
    $admin_userpoints_shopphoto[1] = "Edit offer photo";
    $admin_userpoints_shopphoto[2] = "Edit offer photo";

    $admin_userpoints_shopphoto[3] = "Current photo:";
    $admin_userpoints_shopphoto[4] = "Replace photo with:";
    $admin_userpoints_shopphoto[5] = "Upload";
    $admin_userpoints_shopphoto[6] = "Cancel";

    $admin_userpoints_shopphoto[40] = "Edit offer";
    $admin_userpoints_shopphoto[41] = "Edit photo";
    $admin_userpoints_shopphoto[42] = "Edit comments";
    $admin_userpoints_shopphoto[43] = "Back to Listings";


  break;


  case "admin_userpoints_offercomments":
    $admin_userpoints_offercomments[1] = "Edit offer comments";
    $admin_userpoints_offercomments[2] = "Edit offer comments";
	$admin_userpoints_offercomments[3] = "\o\\n";  //THESE CHARACTERS ARE BEING ESCAPED BECAUSE THEY ARE BEING USED IN A DATE FUNCTION


	$admin_userpoints_offercomments[8] = "select all comments";
	$admin_userpoints_offercomments[9] = "Last Page";
	$admin_userpoints_offercomments[10] = "showing comment";
	$admin_userpoints_offercomments[11] = "of";
	$admin_userpoints_offercomments[12] = "showing comments";
	$admin_userpoints_offercomments[13] = "Next Page";
	$admin_userpoints_offercomments[14] = "No comments have been posted.";
	$admin_userpoints_offercomments[15] = "Anonymous";
  	$admin_userpoints_offercomments[16] = "Delete Selected";

    $admin_userpoints_offercomments[40] = "Edit offer";
    $admin_userpoints_offercomments[41] = "Edit photo";
    $admin_userpoints_offercomments[42] = "Edit comments";
    $admin_userpoints_offercomments[43] = "Back to Listings";


  break;


  case "admin_userpoints_shopcomments":
    $admin_userpoints_shopcomments[1] = "Edit offer comments";
    $admin_userpoints_shopcomments[2] = "Edit offer comments";
	$admin_userpoints_shopcomments[3] = "\o\\n";  //THESE CHARACTERS ARE BEING ESCAPED BECAUSE THEY ARE BEING USED IN A DATE FUNCTION


	$admin_userpoints_shopcomments[8] = "select all comments";
	$admin_userpoints_shopcomments[9] = "Last Page";
	$admin_userpoints_shopcomments[10] = "showing comment";
	$admin_userpoints_shopcomments[11] = "of";
	$admin_userpoints_shopcomments[12] = "showing comments";
	$admin_userpoints_shopcomments[13] = "Next Page";
	$admin_userpoints_shopcomments[14] = "No comments have been posted.";
	$admin_userpoints_shopcomments[15] = "Anonymous";
  	$admin_userpoints_shopcomments[16] = "Delete Selected";

    $admin_userpoints_shopcomments[40] = "Edit offer";
    $admin_userpoints_shopcomments[41] = "Edit photo";
    $admin_userpoints_shopcomments[42] = "Edit comments";
    $admin_userpoints_shopcomments[43] = "Back to Listings";


  break;



  case "admin_userpoints_userquotas":
	$admin_userpoints_userquotas[1] = "Quota and Cumulative Activity Points data for user:";
	$admin_userpoints_userquotas[2] = "This page allows tracking user's activity points and quotas for each action.";
	$admin_userpoints_userquotas[3] = "Action Name";
	$admin_userpoints_userquotas[4] = "Points";
	$admin_userpoints_userquotas[5] = "Save Changes";
	$admin_userpoints_userquotas[6] = "You changes have been saved";
	$admin_userpoints_userquotas[7] = "Requires ";
	$admin_userpoints_userquotas[8] = "Max";
	$admin_userpoints_userquotas[9] = "Rollover period";
	$admin_userpoints_userquotas[10] = "day(s)";

	$admin_userpoints_userquotas[11] = "Current balance";
	$admin_userpoints_userquotas[12] = "Cumulative";
	$admin_userpoints_userquotas[13] = "Last reset";

    break;




  case "admin_userpoints_assign":
	$admin_userpoints_assign[1] = "Assign Activity Points";
	$admin_userpoints_assign[2] = "This page allows assigning various activity points. You can limit maximum amount of accumulated points for a designated period (\"Rollover period\"). Enter 0 for \"Max\" field to disable limiting. Enter 0 for \"Rollover period\" field to make it an all time cap.";
	$admin_userpoints_assign[3] = "Action Name";
	$admin_userpoints_assign[4] = "Points";
	$admin_userpoints_assign[5] = "Save Changes";
	$admin_userpoints_assign[6] = "You changes have been saved";
	$admin_userpoints_assign[7] = "Requires ";
	$admin_userpoints_assign[8] = "Max";
	$admin_userpoints_assign[9] = "Rollover period";
	$admin_userpoints_assign[10] = "day(s)";

	break;

  case "admin_userpoints_viewusers":
	$admin_userpoints_viewusers[1] = "View Users";
	$admin_userpoints_viewusers[2] = "This page lists all of the users that exist on your social network together with their points information. For more information about a specific user, click on the \"edit\" link in its row. Use the filter fields to find specific users based on your criteria. To view all users on your system, leave all the filter fields blank. ";
	$admin_userpoints_viewusers[3] = "Username";
	$admin_userpoints_viewusers[4] = "unverified";
	$admin_userpoints_viewusers[5] = "Email";
	$admin_userpoints_viewusers[6] = "Enabled";
	$admin_userpoints_viewusers[7] = "Signup Date";
	$admin_userpoints_viewusers[8] = "Options ";
	$admin_userpoints_viewusers[9] = "Yes";
	$admin_userpoints_viewusers[10] = "No";

	$admin_userpoints_viewusers[11] = "edit";
	$admin_userpoints_viewusers[12] = "for him";
	$admin_userpoints_viewusers[13] = "by him";

	$admin_userpoints_viewusers[14] = "Filter";
	$admin_userpoints_viewusers[15] = "ID";
	$admin_userpoints_viewusers[16] = "Users Found";
	$admin_userpoints_viewusers[17] = "Page:";

	$admin_userpoints_viewusers[18] = "Points";
	$admin_userpoints_viewusers[19] = "Cumulative";

	$admin_userpoints_viewusers[20] = "IP Address";

	$admin_userpoints_viewusers[21] = "No users were found.";

	$admin_userpoints_viewusers[22] = "Clear all votes";
	$admin_userpoints_viewusers[23] = "Are you sure you want to clear all votes?";

	$admin_userpoints_viewusers[24] = "User Level";
	$admin_userpoints_viewusers[25] = "Subnetwork";
	$admin_userpoints_viewusers[26] = "Default";

	$admin_userpoints_viewusers[27] = "Signup IP Address";


	break;

  case "admin_userpoints_transactions":
	$admin_userpoints_transactions[1] = "Transactions";
	$admin_userpoints_transactions[2] = "View users' points transactions.";
	$admin_userpoints_transactions[3] = "Username";
	$admin_userpoints_transactions[4] = "Transaction status:";
	$admin_userpoints_transactions[5] = "No transaction found.";
	$admin_userpoints_transactions[6] = "Description";

	$admin_userpoints_transactions[9] = "Yes";
	$admin_userpoints_transactions[10] = "No";

	$admin_userpoints_transactions[14] = "Filter";
	$admin_userpoints_transactions[15] = "ID";
	$admin_userpoints_transactions[16] = "Transactions Found";
	$admin_userpoints_transactions[17] = "Page:";

	$admin_userpoints_transactions[18] = "User";
	$admin_userpoints_transactions[19] = "Date";
	$admin_userpoints_transactions[20] = "Description";
	$admin_userpoints_transactions[21] = "Status";
	$admin_userpoints_transactions[22] = "Amount";

	$admin_userpoints_transactions[23] = "TID";


	$admin_userpoints_transactions[24] = "User Level";
	$admin_userpoints_transactions[25] = "Subnetwork";
	$admin_userpoints_transactions[26] = "Default";

	$admin_userpoints_transactions[27] = "confirm";
	$admin_userpoints_transactions[28] = "cancel";

	break;


  case "admin_userpoints_viewusers_edit":
	$admin_userpoints_viewusers_edit[1] = "Edit User:";
	$admin_userpoints_viewusers_edit[2] = "To edit this user's account, make changes to the form below.";
    $admin_userpoints_viewusers_edit[3] = "Total points accumulated:";
    $admin_userpoints_viewusers_edit[4] = "Total points spent:";
	$admin_userpoints_viewusers_edit[5] = "Points:";

	$admin_userpoints_viewusers_edit[6] = "Save Changes";
	$admin_userpoints_viewusers_edit[7] = "Cancel";

	$admin_userpoints_viewusers_edit[8] = "Username";
	$admin_userpoints_viewusers_edit[9] = "Date";
	$admin_userpoints_viewusers_edit[10] = "IP Address";
	$admin_userpoints_viewusers_edit[11] = "Allow points?";

	$admin_userpoints_viewusers_edit[13] = "Last Page";
	$admin_userpoints_viewusers_edit[14] = "viewing friend";
	$admin_userpoints_viewusers_edit[15] = "viewing friends";
	$admin_userpoints_viewusers_edit[16] = "of";
	$admin_userpoints_viewusers_edit[17] = "Next Page";

	$admin_userpoints_viewusers_edit[18] = "Yes";
	$admin_userpoints_viewusers_edit[19] = "No";

	$admin_userpoints_viewusers_edit[20] = "edit";

	break;


  case "admin_userpoints_give":
	$admin_userpoints_give[1] = "Give Points:";
	$admin_userpoints_give[2] = "Give or Set points to all users / group / user. Please note: If you select many users, this process may take a while.";
	$admin_userpoints_give[3] = "Points successfully given!";
	$admin_userpoints_give[4] = "Give Points";
	$admin_userpoints_give[5] = "Give to:";
	$admin_userpoints_give[6] = "Subject";
	$admin_userpoints_give[7] = "Message";
	$admin_userpoints_give[8] = "Give Points";
	$admin_userpoints_give[9] = "Also send message";

	$admin_userpoints_give[10] = "More points!";
	$admin_userpoints_give[11] = "Hi,\n\nI decided to give your more points.\n\nEnjoy!";
	$admin_userpoints_give[12] = "That user doesn't exist.";
	$admin_userpoints_give[13] = "(You can also enter negative amount)";

	break;


  case "admin_levels_userpointssettings":
    $admin_levels_userpointssettings[1] = "Activity Points Settings";
    $admin_levels_userpointssettings[2] = "If you have allowed users to have activity points, you can adjust the details from this page.";
    $admin_levels_userpointssettings[3] = "Allow Activity Points?";
    $admin_levels_userpointssettings[4] = "Do you want to let users have gain points for activities? If set to no, all other settings on this page will not apply.";
    $admin_levels_userpointssettings[5] = "Yes, allow Activity Points.";
    $admin_levels_userpointssettings[6] = "No, do not allow Activity Points.";

    $admin_levels_userpointssettings[7] = "Allow Points Transfer?";
    $admin_levels_userpointssettings[8] = "Do you want to allow users transfer points between themselves? Note: This will limit the SENDER, but not the receiver, (if they are on different levels with different limitation settings).";
    $admin_levels_userpointssettings[9] = "Yes, allow Points Transfer.";
    $admin_levels_userpointssettings[10] = "No, do not allow Points Transfer.";
    $admin_levels_userpointssettings[11] = "You can also limit maximum points transferred per day.";
    $admin_levels_userpointssettings[12] = "Maximum points:";
    $admin_levels_userpointssettings[13] = "(enter 0 to allow unlimited transfers)";

	$admin_levels_userpointssettings[14] = "Save Changes";
	$admin_levels_userpointssettings[15] = "Your changes have been saved.";

	$admin_levels_userpointssettings[35] = "Editing User Level:";
	$admin_levels_userpointssettings[36] = "You are currently editing this user level's settings. Remember, these settings only apply to the users that belong to this user level. When you're finished, you can edit the <a href='admin_levels.php'>other levels here</a>.";

	break;







  /*** USER PAGES ***/




  case "user_points_shop_item":

	$user_points_shop_item[3] = "Cost:";
	$user_points_shop_item[4] = "view(s)";
	$user_points_shop_item[5] = "comment(s)";
	$user_points_shop_item[6] = "Posted on";
	$user_points_shop_item[7] = "Back to Shop Listings";

	$user_points_shop_item[8] = "Comments";
	$user_points_shop_item[11] = "Anonymous";
	$user_points_shop_item[13] = "An Error Has Occurred";
	$user_points_shop_item[14] = "Write Something...";
	$user_points_shop_item[15] = "Posting...";
	$user_points_shop_item[16] = "Please enter a message.";
	$user_points_shop_item[17] = "You have entered the wrong security code.";
	$user_points_shop_item[18] = "Post Comment";
	$user_points_shop_item[19] = "Enter the numbers you see in this image into the field to its right. This helps keep our site free of spam.";
	$user_points_shop_item[20] = "message";

	$user_points_shop_item[21] = "points.";
	$user_points_shop_item[22] = "Buy now";

	$user_points_shop_item[23] = "You successfully bought this item.";

    break;


  case "user_points_offers_item":

	$user_points_offers_item[3] = "Points gained:";
	$user_points_offers_item[4] = "view(s)";
	$user_points_offers_item[5] = "comment(s)";
	$user_points_offers_item[6] = "Posted on";
	$user_points_offers_item[7] = "Back to Shop Listings";

	$user_points_offers_item[8] = "Comments";
	$user_points_offers_item[11] = "Anonymous";
	$user_points_offers_item[13] = "An Error Has Occurred";
	$user_points_offers_item[14] = "Write Something...";
	$user_points_offers_item[15] = "Posting...";
	$user_points_offers_item[16] = "Please enter a message.";
	$user_points_offers_item[17] = "You have entered the wrong security code.";
	$user_points_offers_item[18] = "Post Comment";
	$user_points_offers_item[19] = "Enter the numbers you see in this image into the field to its right. This helps keep our site free of spam.";
	$user_points_offers_item[20] = "message";

	$user_points_offers_item[21] = "points.";
	$user_points_offers_item[22] = "You successfully bought this item.";
	$user_points_offers_item[23] = "Participate";

    break;


  case "user_points_transactions":
	$user_points_transactions[1] = "My Vault";
	$user_points_transactions[2] = "Transaction history";
	$user_points_transactions[3] = "Earn points";
	$user_points_transactions[4] = "Spend points";
	$user_points_transactions[5] = "FAQ";

	$user_points_transactions[10] = "My Transaction history";
	$user_points_transactions[11] = "This transaction history doesn't include points accrued by activities such as posting comments, creating groups, etc.";
	$user_points_transactions[12] = "Search transactions for:";


	$user_points_transactions[15] = "Last Page";
	$user_points_transactions[16] = "viewing transaction";
	$user_points_transactions[17] = "of";
	$user_points_transactions[18] = "viewing transactions";
	$user_points_transactions[19] = "Next Page";
	$user_points_transactions[20] = "No entries matched your search term.";
	$user_points_transactions[21] = "You do not have any transactions.";

	$user_points_transactions[22] = "Date";
	$user_points_transactions[23] = "Description";
	$user_points_transactions[24] = "Status";
	$user_points_transactions[25] = "Amount";
	$user_points_transactions[26] = "Search";

    break;


  case "user_vault":
	$user_vault[1] = "My Vault";
	$user_vault[2] = "Transaction history";
	$user_vault[3] = "Earn points";
	$user_vault[4] = "Spend points";
	$user_vault[5] = "FAQ";

	$user_vault[10] = "My Vault";
	$user_vault[11] = "This is a summary of your account. It includes the total points earned to date and current balance.";
	$user_vault[12] = "You have ";
	$user_vault[13] = "points";
	$user_vault[14] = "Send points to a friend";
	$user_vault[15] = "Receipient:";
	$user_vault[16] = "Amount:";
	$user_vault[17] = "Start typing a friend\\&#039;s name...";
	$user_vault[18] = "No friends found";
	$user_vault[19] = "Type your friend\\&#039;s name";
	$user_vault[20] = "Send";
	$user_vault[25] = "You have accumulated a total of";
	$user_vault[26] = "Your overall rank is";
	$user_vault[27] = "out of";
	$user_vault[28] = "Sending...";

	$user_vault[29] = "BALANCE";
	$user_vault[30] = "TOTAL POINTS EARNED";
	$user_vault[31] = "STAR RATING";
	$user_vault[32] = "place";
	$user_vault[33] = "Not ranked";



    break;

  case "profile":
	$profile[600] = "My Active Points";
	$profile[601] = "My Star Rating is:";
	$profile[602] = "I have";
	$profile[603] = "points.";
	$profile[604] = "I earned a total of:";
  	$profile[605] = "My Rank is:";
  	$profile[606] = "Not ranked";
    break;


  case "user_home":
	$user_home[600] = "My Active Points";
	$user_home[601] = "My star rating is:";
	$user_home[602] = "I have";
	$user_home[603] = "points.";
	$user_home[604] = "I earned a total of ";
  	$user_home[605] = "My rank is: ";
  	$user_home[606] = "Not ranked";
  	$user_home[607] = "Earn more";
  	$user_home[608] = "Spend them";
    break;


  case "user_points_faq":

	$user_points_faq[1] = "My Vault";
	$user_points_faq[2] = "Transaction history";
	$user_points_faq[3] = "Earn points";
	$user_points_faq[4] = "Spend points";
	$user_points_faq[5] = "FAQ";


	$user_points_faq[8] = "Points Frequently Asked Questions";
	$user_points_faq[9] = "If you need help, the answer to your question is likely to be found on this page.";

	$user_points_faq[10] = "Earning Points";
	$user_points_faq[11] = "How do i earn points?";
	$user_points_faq[12] = "You can earn points doing various activities on the site, like <a href='invite.php'>referring friends</a>, uploading your <a href='user_editprofile_photo.php'>profile photo</a>, creating groups, etc. You can also earn points by participating in our <a href='user_points_offers.php'>offers</a>.";
	$user_points_faq[13] = "What activities reward me with points?";
	$user_points_faq[14] = "The following table lists activities and points awarded. Activities can have accumulation limits which are reset after \"Reset Period\"";
	$user_points_faq[15] = "Activity";
	$user_points_faq[16] = "Points";
	$user_points_faq[17] = "Maximum";
	$user_points_faq[18] = "Reset period";
	$user_points_faq[19] = "day(s)";
	$user_points_faq[20] = "Unlimited";
	$user_points_faq[21] = "Never";

	$user_points_faq[50] = "Spending Points";
	$user_points_faq[51] = "How can i spend points?";
	$user_points_faq[52] = "Check out our <a href='user_points_shop.php'>points shop </a> where you can find various ways to spend your points.";

	break;


  case "user_points_offers":

	$user_points_offers[1] = "My Vault";
	$user_points_offers[2] = "Transaction history";
	$user_points_offers[3] = "Earn points";
	$user_points_offers[4] = "Spend points";
	$user_points_offers[5] = "FAQ";

	$user_points_offers[6] = "Last Page";
	$user_points_offers[7] = "showing offers";
	$user_points_offers[8] = "of";
	$user_points_offers[9] = "showing offers";
	$user_points_offers[10] = "Next Page";

	$user_points_offers[11] = "Offers";
	$user_points_offers[12] = "Here you can see how to earn points.";
	$user_points_offers[13] = "Search all offers";
    $user_points_offers[14] = "Posted on";
    $user_points_offers[15] = "views";
    $user_points_offers[16] = "comments";
    $user_points_offers[17] = "Tag Cloud";

	$user_points_offers[20] = "No entries matched your search term.";
	$user_points_offers[21] = "There are no available offers.";

	break;


  case "user_points_shop":

	$user_points_shop[1] = "My Vault";
	$user_points_shop[2] = "Transaction history";
	$user_points_shop[3] = "Earn points";
	$user_points_shop[4] = "Spend points";
	$user_points_shop[5] = "FAQ";

	$user_points_shop[6] = "Last Page";
	$user_points_shop[7] = "showing offers";
	$user_points_shop[8] = "of";
	$user_points_shop[9] = "showing offers";
	$user_points_shop[10] = "Next Page";

	$user_points_shop[11] = "Shop";
	$user_points_shop[12] = "Here you can find ways to spend your hard earned points.";
	$user_points_shop[13] = "Search all offers";
	$user_points_shop[14] = "Posted on";
	$user_points_shop[15] = "views";
	$user_points_shop[16] = "comments";
	$user_points_shop[17] = "Tag Cloud";

	$user_points_shop[20] = "No entries matched your search term.";
	$user_points_shop[21] = "There are no available offers.";

	break;


  case "user_points_shop_item_promote":
    $user_points_shop_item_promote[1] = "Please choose your classified:";
    $user_points_shop_item_promote[2] = "Please choose your event:";
    $user_points_shop_item_promote[3] = "Please choose your group:";
    $user_points_shop_item_promote[4] = "Please choose your poll:";
    $user_points_shop_item_promote[5] = "Continue";
    break;



  case "topusers":
    $topusers[1] = "Our All Time Stars";
    $topusers[2] = "Total points earned:";
    $topusers[3] = "A guide to stardom";
    $topusers[4] = "You can earn points by";
    $topusers[5] = "Uploading photos";
    $topusers[6] = "Making comments (don't spam as you will get punished and have points deducted)";
    $topusers[7] = "Inviting your friends";
    $topusers[8] = "Creating groups";
    $topusers[9] = "(spam control in effect)";
    $topusers[10] = "Tagging your friends on the pictures";
    $topusers[11] = "See more";

    break;


  case "home":
    $home[600] = "Top Users";
    $home[601] = "points";
    $home[602] = "Nobody climbed to the top, yet.";

    break;



}


// ASSIGN ALL SMARTY VARIABLES
if(is_array(${"$page"})) {
  reset(${"$page"});
  while(list($key, $val) = each(${"$page"})) {
    $smarty->assign($page.$key, $val);
  }
}

?>