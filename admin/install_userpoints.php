<?php
$plugin_name = "Activity Points Advanced";
$plugin_version = "3.02";
$plugin_type = "userpoints";
$plugin_desc = "The plugin allows assigning user points for various activities.";
$plugin_icon = "userpoints16.png";
$plugin_menu_title = "100016000";
$plugin_pages_main = "100016001<!>userpoints16.png<!>admin_userpoints_viewusers.php<~!~>100016002<!>userpoints16.png<!>admin_userpoints_assign.php<~!~>100016003<!>userpoints16.png<!>admin_userpoints_give.php<~!~>100016004<!>userpoints16.png<!>admin_userpoints_pointranks.php<~!~>100016006<!>userpoints16.png<!>admin_userpoints_offers.php<~!~>100016007<!>userpoints16.png<!>admin_userpoints_shop.php<~!~>100016008<!>userpoints16.png<!>admin_userpoints_transactions.php<~!~>100016009<!>userpoints16.png<!>admin_userpoints.php";
$plugin_pages_level = "100016010<!>admin_levels_userpointssettings.php";
$plugin_url_htaccess = "";

if($install == "userpoints") {

  //######### INSERT ROW INTO se_plugins
  $database->database_query("INSERT INTO se_plugins (

                  plugin_name,
                  plugin_version,
                  plugin_type,
                  plugin_desc,
                  plugin_icon,
                  plugin_menu_title,
                  plugin_pages_main,
                  plugin_pages_level,
                  plugin_url_htaccess
                  ) VALUES (
                  '$plugin_name',
                  '$plugin_version',
                  '$plugin_type',
                  '".str_replace("'", "\'", $plugin_desc)."',
                  '$plugin_icon',
                  '$plugin_menu_title',
                  '$plugin_pages_main',
                  '$plugin_pages_level',
                  '$plugin_url_htaccess')

                  ON DUPLICATE KEY UPDATE

                  plugin_version='$plugin_version',
                  plugin_desc='".str_replace("'", "\'", $plugin_desc)."',
                  plugin_icon='$plugin_icon',
                  plugin_menu_title='$plugin_menu_title',
                  plugin_pages_main='$plugin_pages_main',
                  plugin_pages_level='$plugin_pages_level',
                  plugin_url_htaccess='$plugin_url_htaccess'

  ");


  //######### INSERT LANGUAGE VARS
  $database->database_query("REPLACE INTO se_languagevars (languagevar_id, languagevar_language_id, languagevar_value, languagevar_default) VALUES (100016000, 1, 'Activity Points', ''),(100016001, 1, 'View Users', ''),(100016002, 1, 'Assign Activity Points', ''),(100016003, 1, 'Give Points', ''),(100016004, 1, 'Point Ranks', ''),(100016005, 1, 'Activity Charging', ''),(100016006, 1, 'Offers', ''),(100016007, 1, 'Points Shop', ''),(100016008, 1, 'Transactions', ''),(100016009, 1, 'Points Settings', ''),(100016010, 1, 'Points Settings', ''),(100016011, 1, 'Points', ''),(100016012, 1, 'Points Shop', ''),(100016013, 1, 'Top Users', ''),(100016014, 1, 'Posting a comment on Points Earning Offer.', ''),(100016015, 1, '<a href=\'profile.php?user=%1\$s\'>%2\$s</a> posted a comment on the offer <a href=\'user_points_offers_item.php?item_id=%3\$s\'>%4\$s</a>:<div class=\'recentaction_div\'>%5\$s</div>', ''),(100016016, 1, 'Posting a comment on Points Shop Item.', ''),(100016017, 1, '<a href=\'profile.php?user=%1\$s\'>%2\$s</a> posted a comment on the points shop item <a href=\'user_points_shop_item.php?shopitem_id=%3\$s\'>%4\$s</a>:<div class=\'recentaction_div\'>%5\$s</div>', ''),(100016018, 1, 'You don\'t have enough points to post classified.', ''),(100016019, 1, 'You don\'t have enough points to create an event.', ''),(100016020, 1, 'You don\'t have enough points to create a group.', ''),(100016021, 1, 'You don\'t have enough points to create a poll.', ''),(100016022, 1, 'Thank you for your order!  You transaction will be approved after we receive confirmation from payment company (usually almost instantly).', ''),(100016023, 1, 'You order was cancelled.', ''),(100016024, 1, 'Completed', ''),(100016025, 1, 'Pending', ''),(100016026, 1, 'Cancelled', ''),(100016027, 1, 'Error. Please contact administrator', ''),(100016028, 1, 'Unsupported item.', ''),(100016029, 1, 'You don\'t have enough points.', ''),(100016030, 1, 'Visiting Affiliate', ''),(100016031, 1, 'Voting for a', ''),(100016032, 1, 'This poll doesn\'t exist.', ''),(100016033, 1, 'poll', ''),(100016034, 1, 'Purchasing points', ''),(100016035, 1, 'Please contact webmaster - Payment module not installed.', ''),(100016036, 1, 'Level Upgrade', ''),(100016037, 1, 'You can\'t upgrade from this level.', ''),(100016038, 1, 'Your level was upgraded!', ''),(100016039, 1, 'Promoting my profile', ''),(100016040, 1, 'Invalid template, please contact administrator.', ''),(100016041, 1, 'Invalid classified', ''),(100016042, 1, 'Promoting my', ''),(100016043, 1, 'classified', ''),(100016044, 1, 'Invalid event', ''),(100016045, 1, 'event', ''),(100016046, 1, 'Invalid group', ''),(100016047, 1, 'group', ''),(100016048, 1, 'Invalid poll', ''),(100016049, 1, 'poll', ''),(100016050, 1, 'Your promotion has been accepted', ''),(100016051, 1, ' and will start immediately.', ''),(100016052, 1, ' and will be active starting from tomorrow.', ''),(100016053, 1, 'You don\'t have any classifieds.', ''),(100016054, 1, 'You don\'t have any events.', ''),(100016055, 1, 'You don\'t have any groups.', ''),(100016056, 1, 'You don\'t have any polls.', ''),(100016057, 1, 'You don\'t have enough points.', ''),(100016058, 1, 'Please enter amount greater than zero.', ''),(100016059, 1, 'Recipient doesn\'t exist or can\'t accept points.', ''),(100016060, 1, 'Points were transferred successfully.', ''),(100016061, 1, 'You are not allowed to transfer points.', ''),(100016062, 1, 'You can only transfer %d points.', ''),(100016063, 1, 'You have used up your transfer quota.', ''),(100016064, 1, 'Points transfer to', ''),(100016065, 1, 'Points transfer from', ''),(100016066, 1, 'Generic', ''),(100016067, 1, 'Activity Statistics:', ''),(100016068, 1, 'Use this page to monitor activity of a user on your network.', ''),(100016069, 1, 'Week of', ''),(100016070, 1, ' (', ''),(100016071, 1, ')', ''),(100016072, 1, 'Period:', ''),(100016073, 1, 'This Week (Daily)', ''),(100016074, 1, 'This Month (Daily)', ''),(100016075, 1, 'This Year (Monthly)', ''),(100016076, 1, 'Refresh', ''),(100016077, 1, 'Imported contacts', ''),(100016078, 1, 'Invited contacts', ''),(100016079, 1, 'Points Earned', ''),(100016080, 1, 'Points Spent', ''),(100016081, 1, 'User', ''),(100016082, 1, 'No data.', ''),(100016083, 1, 'Last Period', ''),(100016084, 1, 'Next Period', ''),(100016085, 1, 'Transactions for user:', ''),(100016086, 1, 'View users\' points transactions.', ''),(100016087, 1, 'Username', ''),(100016088, 1, 'Transaction status:', ''),(100016089, 1, 'No transaction found.', ''),(100016090, 1, 'Description', ''),(100016091, 1, 'Yes', ''),(100016092, 1, 'No', ''),(100016093, 1, 'Filter', ''),(100016094, 1, 'ID', ''),(100016095, 1, 'Transactions Found', ''),(100016096, 1, 'Page:', ''),(100016097, 1, 'User', ''),(100016098, 1, 'Date', ''),(100016099, 1, 'Description', ''),(100016100, 1, 'Status', ''),(100016101, 1, 'Amount', ''),(100016102, 1, 'TID', ''),(100016103, 1, 'User Level', ''),(100016104, 1, 'Subnetwork', ''),(100016105, 1, 'Default', ''),(100016106, 1, 'confirm', ''),(100016107, 1, 'cancel', ''),(100016108, 1, 'General Activity Points Settings', ''),(100016109, 1, 'This page contains general Activity Points settings.', ''),(100016110, 1, 'Your changes have been saved.', ''),(100016111, 1, 'Enable Top Users?', ''),(100016112, 1, 'This will show a page of top users ranked by total amount of accumulated points (regardless of their current points \"balance\") and also show user\'s rank on profile and user homepage.', ''),(100016113, 1, 'Yes, enable Top Users.', ''),(100016114, 1, 'No, disable Top Users.', ''),(100016115, 1, 'Save Changes', ''),(100016116, 1, 'Enable Offers (Earn Points)?', ''),(100016117, 1, 'Do you want to allow users to view \"Earn Points\" page and participate in offers for gaining points?', ''),(100016118, 1, 'Yes, enable Offers.', ''),(100016119, 1, 'No, disable Offers.', ''),(100016120, 1, 'Enable Points Shop (Spend Points)?', ''),(100016121, 1, 'Do you want to allow users to view \"Spend Points\" page and purchase items?', ''),(100016122, 1, 'Yes, enable Points Shop.', ''),(100016123, 1, 'No, disable Points Shop.', ''),(100016124, 1, 'Enable Activity Statistics?', ''),(100016125, 1, 'Do you want to gather daily points activity statistics per user?', ''),(100016126, 1, 'Yes, enable Activity Statistics.', ''),(100016127, 1, 'No, disable Activity Statistics.', ''),(100016128, 1, 'Points Rankings', ''),(100016129, 1, 'This page allows setting rankings based on total earned points count.', ''),(100016130, 1, 'Your changes have been saved.', ''),(100016131, 1, 'Enable Points Ranking?', ''),(100016132, 1, 'Control if you want to show rank calculated from user\'s total earned points till date based on table below on his profile and user home page.', ''),(100016133, 1, 'Yes, enable points ranking.', ''),(100016134, 1, 'No, disable points ranking.', ''),(100016135, 1, 'Rankings', ''),(100016136, 1, 'Rankings', ''),(100016137, 1, 'Points from', ''),(100016138, 1, 'Rank Title', ''),(100016139, 1, '+ Add more', ''),(100016140, 1, 'Save Changes', ''),(100016141, 1, 'Charging for preset actions.', ''),(100016142, 1, 'This page allows setting costs for posting / creating new events, classifieds, groups and polls. <br>If you enable charging, DO NOT FORGET to <strong>zero activity points</strong> assigned for same action, e.g. creating group.', ''),(100016143, 1, 'Your changes have been saved.', ''),(100016144, 1, 'Classifieds', ''),(100016145, 1, 'Select whether or not you want to charge for posting a new classified.', ''),(100016146, 1, 'Yes, charge for posting a classified', ''),(100016147, 1, 'No, do not charge for posting a classified ', ''),(100016148, 1, 'If you have selected \"Yes\" above, please specify points cost for posting a classified.', ''),(100016149, 1, 'points', ''),(100016150, 1, 'Groups', ''),(100016151, 1, 'Select whether or not you want to charge for posting a new group.', ''),(100016152, 1, 'Yes, charge for posting a group', ''),(100016153, 1, 'No, do not charge for posting a group', ''),(100016154, 1, 'If you have selected \"Yes\" above, please specify points cost for posting a group.', ''),(100016155, 1, 'Polls', ''),(100016156, 1, 'Select whether or not you want to charge for posting a new poll.', ''),(100016157, 1, 'Yes, charge for posting a poll', ''),(100016158, 1, 'No, do not charge for posting a poll', ''),(100016159, 1, 'If you have selected \"Yes\" above, please specify points cost for posting a poll.', ''),(100016160, 1, 'Events', ''),(100016161, 1, 'Select whether or not you want to charge for posting a new event.', ''),(100016162, 1, 'Yes, charge for posting an event', ''),(100016163, 1, 'No, do not charge for posting a event', ''),(100016164, 1, 'If you have selected \"Yes\" above, please specify points cost for posting an event.', ''),(100016165, 1, 'Save Changes', ''),(100016166, 1, 'View Offers', ''),(100016167, 1, 'This page lists all of the offers available for users. For more information about a specific offer, click on the \"edit\" link in its row. Use the filter fields to find specific offer based on your criteria. To view all offers, leave all the filter fields blank. ', ''),(100016168, 1, 'Title', ''),(100016169, 1, 'Type', ''),(100016170, 1, 'Levels', ''),(100016171, 1, 'Subnets', ''),(100016172, 1, 'Views', ''),(100016173, 1, 'Comments', ''),(100016174, 1, 'Yes', ''),(100016175, 1, 'No', ''),(100016176, 1, 'edit', ''),(100016177, 1, 'disable', ''),(100016178, 1, 'Add date', ''),(100016179, 1, 'Filter', ''),(100016180, 1, 'delete', ''),(100016181, 1, 'Offers Found', ''),(100016182, 1, 'Page:', ''),(100016183, 1, 'Delete User', ''),(100016184, 1, 'Are you sure you want to delete this offer?', ''),(100016185, 1, 'Cancel', ''),(100016186, 1, 'No offers were found.', ''),(100016187, 1, 'enable', ''),(100016188, 1, 'Add New Offer', ''),(100016189, 1, 'User Level', ''),(100016190, 1, 'Subnetwork', ''),(100016191, 1, 'Default', ''),(100016192, 1, 'Disable Selected', ''),(100016193, 1, 'Enabled', ''),(100016194, 1, 'Options', ''),(100016195, 1, 'Points Gain', ''),(100016196, 1, 'Choose type:', ''),(100016197, 1, 'Acts', ''),(100016198, 1, 'View Shop Items', ''),(100016199, 1, 'This page lists all of the items available for users to purchase. For more information about a specific item, click on the \"edit\" link in its row. Use the filter fields to find specific item based on your criteria. To view all items, leave all the filter fields blank. ', ''),(100016200, 1, 'Title', ''),(100016201, 1, 'Type', ''),(100016202, 1, 'Levels', ''),(100016203, 1, 'Subnets', ''),(100016204, 1, 'Views', ''),(100016205, 1, 'Comments', ''),(100016206, 1, 'Yes', ''),(100016207, 1, 'No', ''),(100016208, 1, 'edit', ''),(100016209, 1, 'disable', ''),(100016210, 1, 'Add date', ''),(100016211, 1, 'Filter', ''),(100016212, 1, 'delete', ''),(100016213, 1, 'Items Found', ''),(100016214, 1, 'Page:', ''),(100016215, 1, 'Delete User', ''),(100016216, 1, 'Are you sure you want to delete this offer?', ''),(100016217, 1, 'Cancel', ''),(100016218, 1, 'No offers were found.', ''),(100016219, 1, 'enable', ''),(100016220, 1, 'Add New Item', ''),(100016221, 1, 'User Level', ''),(100016222, 1, 'Subnetwork', ''),(100016223, 1, 'Default', ''),(100016224, 1, 'Disable Selected', ''),(100016225, 1, 'Enabled', ''),(100016226, 1, 'Options', ''),(100016227, 1, 'Cost', ''),(100016228, 1, 'Acts', ''),(100016229, 1, 'Choose type:', ''),(100016230, 1, 'Add Shop Item - Generic', ''),(100016231, 1, 'Add Shop Item - Generic', ''),(100016232, 1, 'Title:', ''),(100016233, 1, 'Description:', ''),(100016234, 1, 'Redirect URL:', ''),(100016235, 1, 'Show in transactions?', ''),(100016236, 1, 'Transaction state:', ''),(100016237, 1, 'Cost:', ''),(100016238, 1, 'Save offer', ''),(100016239, 1, 'Cancel', ''),(100016240, 1, 'Enabled?', ''),(100016241, 1, 'Enabled', ''),(100016242, 1, 'Disabled', ''),(100016243, 1, 'User levels', ''),(100016244, 1, 'Subnets', ''),(100016245, 1, 'Tags:', ''),(100016246, 1, 'Allow comments?', ''),(100016247, 1, 'Completed', ''),(100016248, 1, 'Pending', ''),(100016249, 1, 'Yes', ''),(100016250, 1, 'No', ''),(100016251, 1, 'Changes saved.', ''),(100016252, 1, '(signup default)', ''),(100016253, 1, 'Levels:', ''),(100016254, 1, 'Subnets:', ''),(100016255, 1, 'Select options', ''),(100016256, 1, 'Select all', ''),(100016257, 1, 'Edit offer', ''),(100016258, 1, 'Edit photo', ''),(100016259, 1, 'Edit comments', ''),(100016260, 1, 'Back to Listings', ''),(100016261, 1, 'Please enter title.', ''),(100016262, 1, 'Add Offer - Generic', ''),(100016263, 1, 'Add Offer - Generic', ''),(100016264, 1, 'Title:', ''),(100016265, 1, 'Description:', ''),(100016266, 1, 'Redirect URL:', ''),(100016267, 1, 'Show in transactions?', ''),(100016268, 1, 'Transaction state:', ''),(100016269, 1, 'Points:', ''),(100016270, 1, 'Save offer', ''),(100016271, 1, 'Cancel', ''),(100016272, 1, 'Enabled?', ''),(100016273, 1, 'Enabled', ''),(100016274, 1, 'Disabled', ''),(100016275, 1, 'User levels', ''),(100016276, 1, 'Subnets', ''),(100016277, 1, 'Tags:', ''),(100016278, 1, 'Allow comments?', ''),(100016279, 1, 'Completed', ''),(100016280, 1, 'Pending', ''),(100016281, 1, 'Yes', ''),(100016282, 1, 'No', ''),(100016283, 1, 'Changes saved.', ''),(100016284, 1, '(signup default)', ''),(100016285, 1, 'Levels:', ''),(100016286, 1, 'Subnets:', ''),(100016287, 1, 'Select options', ''),(100016288, 1, 'Select all', ''),(100016289, 1, 'Edit offer', ''),(100016290, 1, 'Edit photo', ''),(100016291, 1, 'Edit comments', ''),(100016292, 1, 'Back to Listings', ''),(100016293, 1, 'Please enter title.', ''),(100016294, 1, 'Add Offer - Level Upgrade', ''),(100016295, 1, 'Add Offer - Level Upgrade', ''),(100016296, 1, 'Title:', ''),(100016297, 1, 'Description:', ''),(100016298, 1, 'Level from:', ''),(100016299, 1, 'Level to:', ''),(100016300, 1, 'Any', ''),(100016301, 1, 'Cost', ''),(100016302, 1, 'Save offer', ''),(100016303, 1, 'Cancel', ''),(100016304, 1, 'Enabled?', ''),(100016305, 1, 'Enabled', ''),(100016306, 1, 'Disabled', ''),(100016307, 1, 'User levels', ''),(100016308, 1, 'Subnets', ''),(100016309, 1, 'Tags:', ''),(100016310, 1, 'Allow comments?', ''),(100016311, 1, 'Yes', ''),(100016312, 1, 'No', ''),(100016313, 1, 'Changes saved.', ''),(100016314, 1, '(signup default)', ''),(100016315, 1, 'Levels:', ''),(100016316, 1, 'Subnets:', ''),(100016317, 1, 'Select options', ''),(100016318, 1, 'Select all', ''),(100016319, 1, 'Edit offer', ''),(100016320, 1, 'Edit photo', ''),(100016321, 1, 'Edit comments', ''),(100016322, 1, 'Back to Listings', ''),(100016323, 1, 'Please enter title.', ''),(100016324, 1, 'Add Offer - Promotion', ''),(100016325, 1, 'Promotion allows your users to create ad campaigns on their content - profile, classified, event, group or poll.', ''),(100016326, 1, 'Title:', ''),(100016327, 1, 'Description:', ''),(100016328, 1, 'Level from:', ''),(100016329, 1, 'Level to:', ''),(100016330, 1, 'Any', ''),(100016331, 1, 'Cost', ''),(100016332, 1, 'Save offer', ''),(100016333, 1, 'Cancel', ''),(100016334, 1, 'Enabled?', ''),(100016335, 1, 'Enabled', ''),(100016336, 1, 'Disabled', ''),(100016337, 1, 'User levels', ''),(100016338, 1, 'Subnets', ''),(100016339, 1, 'Tags:', ''),(100016340, 1, 'Allow comments?', ''),(100016341, 1, '(reset html)', ''),(100016342, 1, 'Promotion template:', ''),(100016343, 1, 'Promotion type:', ''),(100016344, 1, 'Ad html', ''),(100016345, 1, 'Require approval?', ''),(100016346, 1, 'Yes', ''),(100016347, 1, 'No', ''),(100016348, 1, 'Start', ''),(100016349, 1, 'Immediately', ''),(100016350, 1, 'Delay for one day', ''),(100016351, 1, 'Duration:', ''),(100016352, 1, 'day(s)', ''),(100016353, 1, '(signup default)', ''),(100016354, 1, 'Levels:', ''),(100016355, 1, 'Subnets:', ''),(100016356, 1, 'Select options', ''),(100016357, 1, 'Select all', ''),(100016358, 1, 'Changes saved.', ''),(100016359, 1, 'Edit offer', ''),(100016360, 1, 'Edit photo', ''),(100016361, 1, 'Edit comments', ''),(100016362, 1, 'Back to Listings', ''),(100016363, 1, 'Please enter title.', ''),(100016364, 1, 'Please choose a promotion type.', ''),(100016365, 1, 'You don\'t have Classifieds plugin installed.', ''),(100016366, 1, 'You don\'t have Events plugin installed.', ''),(100016367, 1, 'You don\'t have Groups plugin installed.', ''),(100016368, 1, 'You don\'t have Polls plugin installed.', ''),(100016369, 1, 'Add Offer - Vote Poll', ''),(100016370, 1, 'Here you can pitch a specific poll for your users.', ''),(100016371, 1, 'Title:', ''),(100016372, 1, 'Description:', ''),(100016373, 1, 'Poll ID:', ''),(100016374, 1, 'Any', ''),(100016375, 1, 'Points:', ''),(100016376, 1, 'Save offer', ''),(100016377, 1, 'Cancel', ''),(100016378, 1, 'Enabled?', ''),(100016379, 1, 'Enabled', ''),(100016380, 1, 'Disabled', ''),(100016381, 1, 'User levels', ''),(100016382, 1, 'Subnets', ''),(100016383, 1, 'Tags:', ''),(100016384, 1, 'Allow comments?', ''),(100016385, 1, 'Points added:', ''),(100016386, 1, 'Immediately', ''),(100016387, 1, 'Require action', ''),(100016388, 1, 'This poll doesn\'t exist.', ''),(100016389, 1, 'Yes', ''),(100016390, 1, 'No', ''),(100016391, 1, 'Changes saved.', ''),(100016392, 1, '(signup default)', ''),(100016393, 1, 'Levels:', ''),(100016394, 1, 'Subnets:', ''),(100016395, 1, 'Select options', ''),(100016396, 1, 'Select all', ''),(100016397, 1, 'You don\'t have polls plugin installed!', ''),(100016398, 1, 'Edit offer', ''),(100016399, 1, 'Edit photo', ''),(100016400, 1, 'Edit comments', ''),(100016401, 1, 'Back to Listings', ''),(100016402, 1, 'Please enter title.', ''),(100016403, 1, 'Add Offer - Affiliate', ''),(100016404, 1, 'This type of offer allows you to redirect a user to your affiliate, adding customer parameters such as user id, username or transaction id.', ''),(100016405, 1, 'Title:', ''),(100016406, 1, 'Description:', ''),(100016407, 1, 'Affiliate URL:', ''),(100016408, 1, 'Any', ''),(100016409, 1, 'Points:', ''),(100016410, 1, 'Save offer', ''),(100016411, 1, 'Cancel', ''),(100016412, 1, 'Enabled?', ''),(100016413, 1, 'Enabled', ''),(100016414, 1, 'Disabled', ''),(100016415, 1, 'User levels', ''),(100016416, 1, 'Subnets', ''),(100016417, 1, 'Tags:', ''),(100016418, 1, 'Allow comments?', ''),(100016419, 1, 'Points added:', ''),(100016420, 1, 'Immediately', ''),(100016421, 1, 'Require action', ''),(100016422, 1, 'This poll doesn\'t exist.', ''),(100016423, 1, 'Yes', ''),(100016424, 1, 'No', ''),(100016425, 1, 'Changes saved.', ''),(100016426, 1, '(signup default)', ''),(100016427, 1, 'Levels:', ''),(100016428, 1, 'Subnets:', ''),(100016429, 1, 'Select options', ''),(100016430, 1, 'Select all', ''),(100016431, 1, '(Available parameters: [userid], [username], [transactionid])', ''),(100016432, 1, 'Edit offer', ''),(100016433, 1, 'Edit photo', ''),(100016434, 1, 'Edit comments', ''),(100016435, 1, 'Back to Listings', ''),(100016436, 1, 'Please enter title.', ''),(100016437, 1, 'Add Offer - Direct purchase', ''),(100016438, 1, 'Direct purchasing allows your members to exchange real money for points.', ''),(100016439, 1, 'Title:', ''),(100016440, 1, 'Description:', ''),(100016441, 1, 'Payment Gateways:', ''),(100016442, 1, 'Any', ''),(100016443, 1, 'Points:', ''),(100016444, 1, 'Save offer', ''),(100016445, 1, 'Cancel', ''),(100016446, 1, 'Enabled?', ''),(100016447, 1, 'Enabled', ''),(100016448, 1, 'Disabled', ''),(100016449, 1, 'User levels', ''),(100016450, 1, 'Subnets', ''),(100016451, 1, 'Tags:', ''),(100016452, 1, 'Allow comments?', ''),(100016453, 1, 'Points added:', ''),(100016454, 1, 'Immediately', ''),(100016455, 1, 'Require action', ''),(100016456, 1, 'This poll doesn\'t exist.', ''),(100016457, 1, 'Yes', ''),(100016458, 1, 'No', ''),(100016459, 1, 'Changes saved.', ''),(100016460, 1, '(signup default)', ''),(100016461, 1, 'Levels:', ''),(100016462, 1, 'Subnets:', ''),(100016463, 1, 'Select options', ''),(100016464, 1, 'Select all', ''),(100016465, 1, 'Edit offer', ''),(100016466, 1, 'Edit photo', ''),(100016467, 1, 'Edit comments', ''),(100016468, 1, 'Back to Listings', ''),(100016469, 1, 'Please enter title.', ''),(100016470, 1, 'Edit offer photo', ''),(100016471, 1, 'Edit offer photo', ''),(100016472, 1, 'Current photo:', ''),(100016473, 1, 'Replace photo with:', ''),(100016474, 1, 'Upload', ''),(100016475, 1, 'Cancel', ''),(100016476, 1, 'Edit offer', ''),(100016477, 1, 'Edit photo', ''),(100016478, 1, 'Edit comments', ''),(100016479, 1, 'Back to Listings', ''),(100016480, 1, 'Edit offer photo', ''),(100016481, 1, 'Edit offer photo', ''),(100016482, 1, 'Current photo:', ''),(100016483, 1, 'Replace photo with:', ''),(100016484, 1, 'Upload', ''),(100016485, 1, 'Cancel', ''),(100016486, 1, 'Edit offer', ''),(100016487, 1, 'Edit photo', ''),(100016488, 1, 'Edit comments', ''),(100016489, 1, 'Back to Listings', ''),(100016490, 1, 'Edit offer comments', ''),(100016491, 1, 'Edit offer comments', ''),(100016492, 1, '\o\\n', ''),(100016493, 1, 'select all comments', ''),(100016494, 1, 'Last Page', ''),(100016495, 1, 'showing comment', ''),(100016496, 1, 'of', ''),(100016497, 1, 'showing comments', ''),(100016498, 1, 'Next Page', ''),(100016499, 1, 'No comments have been posted.', ''),(100016500, 1, 'Anonymous', ''),(100016501, 1, 'Delete Selected', ''),(100016502, 1, 'Edit offer', ''),(100016503, 1, 'Edit photo', ''),(100016504, 1, 'Edit comments', ''),(100016505, 1, 'Back to Listings', ''),(100016506, 1, 'Edit offer comments', ''),(100016507, 1, 'Edit offer comments', ''),(100016508, 1, '\o\\n', ''),(100016509, 1, 'select all comments', ''),(100016510, 1, 'Last Page', ''),(100016511, 1, 'showing comment', ''),(100016512, 1, 'of', ''),(100016513, 1, 'showing comments', ''),(100016514, 1, 'Next Page', ''),(100016515, 1, 'No comments have been posted.', ''),(100016516, 1, 'Anonymous', ''),(100016517, 1, 'Delete Selected', ''),(100016518, 1, 'Edit offer', ''),(100016519, 1, 'Edit photo', ''),(100016520, 1, 'Edit comments', ''),(100016521, 1, 'Back to Listings', ''),(100016522, 1, 'Quota and Cumulative Activity Points data for user:', ''),(100016523, 1, 'This page allows tracking user\'s activity points and quotas for each action.', ''),(100016524, 1, 'Action Name', ''),(100016525, 1, 'Points', ''),(100016526, 1, 'Save Changes', ''),(100016527, 1, 'You changes have been saved', ''),(100016528, 1, 'Requires ', ''),(100016529, 1, 'Max', ''),(100016530, 1, 'Rollover period', ''),(100016531, 1, 'day(s)', ''),(100016532, 1, 'Current balance', ''),(100016533, 1, 'Cumulative', ''),(100016534, 1, 'Last reset', ''),(100016535, 1, 'Assign Activity Points', ''),(100016536, 1, 'This page allows assigning various activity points. You can limit maximum amount of accumulated points for a designated period (\"Rollover period\"). Enter 0 for \"Max\" field to disable limiting. Enter 0 for \"Rollover period\" field to make it an all time cap.', ''),(100016537, 1, 'Action Name', ''),(100016538, 1, 'Points', ''),(100016539, 1, 'Save Changes', ''),(100016540, 1, 'You changes have been saved', ''),(100016541, 1, 'Requires ', ''),(100016542, 1, 'Max', ''),(100016543, 1, 'Rollover period', ''),(100016544, 1, 'day(s)', ''),(100016545, 1, 'View Users', ''),(100016546, 1, 'This page lists all of the users that exist on your social network together with their points information. For more information about a specific user, click on the \"edit\" link in its row. Use the filter fields to find specific users based on your criteria. To view all users on your system, leave all the filter fields blank. ', ''),(100016547, 1, 'Username', ''),(100016548, 1, 'unverified', ''),(100016549, 1, 'Email', ''),(100016550, 1, 'Enabled', ''),(100016551, 1, 'Signup Date', ''),(100016552, 1, 'Options ', ''),(100016553, 1, 'Yes', ''),(100016554, 1, 'No', ''),(100016555, 1, 'edit', ''),(100016556, 1, 'for him', ''),(100016557, 1, 'by him', ''),(100016558, 1, 'Filter', ''),(100016559, 1, 'ID', ''),(100016560, 1, 'Users Found', ''),(100016561, 1, 'Page:', ''),(100016562, 1, 'Points', ''),(100016563, 1, 'Cumulative', ''),(100016564, 1, 'IP Address', ''),(100016565, 1, 'No users were found.', ''),(100016566, 1, 'Clear all votes', ''),(100016567, 1, 'Are you sure you want to clear all votes?', ''),(100016568, 1, 'User Level', ''),(100016569, 1, 'Subnetwork', ''),(100016570, 1, 'Default', ''),(100016571, 1, 'Signup IP Address', ''),(100016572, 1, 'Transactions', ''),(100016573, 1, 'View users\' points transactions.', ''),(100016574, 1, 'Username', ''),(100016575, 1, 'Transaction status:', ''),(100016576, 1, 'No transaction found.', ''),(100016577, 1, 'Description', ''),(100016578, 1, 'Yes', ''),(100016579, 1, 'No', ''),(100016580, 1, 'Filter', ''),(100016581, 1, 'ID', ''),(100016582, 1, 'Transactions Found', ''),(100016583, 1, 'Page:', ''),(100016584, 1, 'User', ''),(100016585, 1, 'Date', ''),(100016586, 1, 'Description', ''),(100016587, 1, 'Status', ''),(100016588, 1, 'Amount', ''),(100016589, 1, 'TID', ''),(100016590, 1, 'User Level', ''),(100016591, 1, 'Subnetwork', ''),(100016592, 1, 'Default', ''),(100016593, 1, 'confirm', ''),(100016594, 1, 'cancel', ''),(100016595, 1, 'Edit User:', ''),(100016596, 1, 'To edit this user\'s account, make changes to the form below.', ''),(100016597, 1, 'Total points accumulated:', ''),(100016598, 1, 'Total points spent:', ''),(100016599, 1, 'Points:', ''),(100016600, 1, 'Save Changes', ''),(100016601, 1, 'Cancel', ''),(100016602, 1, 'Username', ''),(100016603, 1, 'Date', ''),(100016604, 1, 'IP Address', ''),(100016605, 1, 'Allow points?', ''),(100016606, 1, 'Last Page', ''),(100016607, 1, 'viewing friend', ''),(100016608, 1, 'viewing friends', ''),(100016609, 1, 'of', ''),(100016610, 1, 'Next Page', ''),(100016611, 1, 'Yes', ''),(100016612, 1, 'No', ''),(100016613, 1, 'edit', ''),(100016614, 1, 'Give Points:', ''),(100016615, 1, 'Give or Set points to all users / group / user. Please note: If you select many users, this process may take a while.', ''),(100016616, 1, 'Points successfully given!', ''),(100016617, 1, 'Give Points', ''),(100016618, 1, 'Give to:', ''),(100016619, 1, 'Subject', ''),(100016620, 1, 'Message', ''),(100016621, 1, 'Give Points', ''),(100016622, 1, 'Also send message', ''),(100016623, 1, 'More points!', ''),(100016624, 1, 'Hi,\n\nI decided to give your more points.\n\nEnjoy!', ''),(100016625, 1, 'That user doesn\'t exist.', ''),(100016626, 1, '(You can also enter negative amount)', ''),(100016627, 1, 'Activity Points Settings', ''),(100016628, 1, 'If you have allowed users to have activity points, you can adjust the details from this page.', ''),(100016629, 1, 'Allow Activity Points?', ''),(100016630, 1, 'Do you want to let users have gain points for activities? If set to no, all other settings on this page will not apply.', ''),(100016631, 1, 'Yes, allow Activity Points.', ''),(100016632, 1, 'No, do not allow Activity Points.', ''),(100016633, 1, 'Allow Points Transfer?', ''),(100016634, 1, 'Do you want to allow users transfer points between themselves? Note: This will limit the SENDER, but not the receiver, (if they are on different levels with different limitation settings).', ''),(100016635, 1, 'Yes, allow Points Transfer.', ''),(100016636, 1, 'No, do not allow Points Transfer.', ''),(100016637, 1, 'You can also limit maximum points transferred per day.', ''),(100016638, 1, 'Maximum points:', ''),(100016639, 1, '(enter 0 to allow unlimited transfers)', ''),(100016640, 1, 'Save Changes', ''),(100016641, 1, 'Your changes have been saved.', ''),(100016642, 1, 'Editing User Level:', ''),(100016643, 1, 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the <a href=\'admin_levels.php\'>other levels here</a>.', ''),(100016644, 1, 'Cost:', ''),(100016645, 1, 'view(s)', ''),(100016646, 1, 'comment(s)', ''),(100016647, 1, 'Posted on', ''),(100016648, 1, 'Back to Shop Listings', ''),(100016649, 1, 'Comments', ''),(100016650, 1, 'Anonymous', ''),(100016651, 1, 'An Error Has Occurred', ''),(100016652, 1, 'Write Something...', ''),(100016653, 1, 'Posting...', ''),(100016654, 1, 'Please enter a message.', ''),(100016655, 1, 'You have entered the wrong security code.', ''),(100016656, 1, 'Post Comment', ''),(100016657, 1, 'Enter the numbers you see in this image into the field to its right. This helps keep our site free of spam.', ''),(100016658, 1, 'message', ''),(100016659, 1, 'points.', ''),(100016660, 1, 'Buy now', ''),(100016661, 1, 'You successfully bought this item.', ''),(100016662, 1, 'Points gained:', ''),(100016663, 1, 'view(s)', ''),(100016664, 1, 'comment(s)', ''),(100016665, 1, 'Posted on', ''),(100016666, 1, 'Back to Shop Listings', ''),(100016667, 1, 'Comments', ''),(100016668, 1, 'Anonymous', ''),(100016669, 1, 'An Error Has Occurred', ''),(100016670, 1, 'Write Something...', ''),(100016671, 1, 'Posting...', ''),(100016672, 1, 'Please enter a message.', ''),(100016673, 1, 'You have entered the wrong security code.', ''),(100016674, 1, 'Post Comment', ''),(100016675, 1, 'Enter the numbers you see in this image into the field to its right. This helps keep our site free of spam.', ''),(100016676, 1, 'message', ''),(100016677, 1, 'points.', ''),(100016678, 1, 'You successfully bought this item.', ''),(100016679, 1, 'Participate', ''),(100016680, 1, 'My Vault', ''),(100016681, 1, 'Transaction history', ''),(100016682, 1, 'Earn points', ''),(100016683, 1, 'Spend points', ''),(100016684, 1, 'FAQ', ''),(100016685, 1, 'My Transaction history', ''),(100016686, 1, 'This transaction history doesn\'t include points accrued by activities such as posting comments, creating groups, etc.', ''),(100016687, 1, 'Search transactions for:', ''),(100016688, 1, 'Last Page', ''),(100016689, 1, 'viewing transaction', ''),(100016690, 1, 'of', ''),(100016691, 1, 'viewing transactions', ''),(100016692, 1, 'Next Page', ''),(100016693, 1, 'No entries matched your search term.', ''),(100016694, 1, 'You do not have any transactions.', ''),(100016695, 1, 'Date', ''),(100016696, 1, 'Description', ''),(100016697, 1, 'Status', ''),(100016698, 1, 'Amount', ''),(100016699, 1, 'Search', ''),(100016700, 1, 'My Vault', ''),(100016701, 1, 'Transaction history', ''),(100016702, 1, 'Earn points', ''),(100016703, 1, 'Spend points', ''),(100016704, 1, 'FAQ', ''),(100016705, 1, 'My Vault', ''),(100016706, 1, 'This is a summary of your account. It includes the total points earned to date and current balance.', ''),(100016707, 1, 'You have ', ''),(100016708, 1, 'points', ''),(100016709, 1, 'Send points to a friend', ''),(100016710, 1, 'Receipient:', ''),(100016711, 1, 'Amount:', ''),(100016712, 1, 'Start typing a friend\\\\\'s name...', ''),(100016713, 1, 'No friends found', ''),(100016714, 1, 'Type your friend\\\\\'s name', ''),(100016715, 1, 'Send', ''),(100016716, 1, 'You have accumulated a total of', ''),(100016717, 1, 'Your overall rank is', ''),(100016718, 1, 'out of', ''),(100016719, 1, 'Sending...', ''),(100016720, 1, 'BALANCE', ''),(100016721, 1, 'TOTAL POINTS EARNED', ''),(100016722, 1, 'STAR RATING', ''),(100016723, 1, 'place', ''),(100016724, 1, 'Not ranked', ''),(100016725, 1, 'My Active Points', ''),(100016726, 1, 'My Star Rating is:', ''),(100016727, 1, 'I have', ''),(100016728, 1, 'points.', ''),(100016729, 1, 'I earned a total of:', ''),(100016730, 1, 'My Rank is:', ''),(100016731, 1, 'Not ranked', ''),(100016732, 1, 'My Active Points', ''),(100016733, 1, 'My star rating is:', ''),(100016734, 1, 'I have', ''),(100016735, 1, 'points.', ''),(100016736, 1, 'I earned a total of ', ''),(100016737, 1, 'My rank is: ', ''),(100016738, 1, 'Not ranked', ''),(100016739, 1, 'Earn more', ''),(100016740, 1, 'Spend them', ''),(100016741, 1, 'My Vault', ''),(100016742, 1, 'Transaction history', ''),(100016743, 1, 'Earn points', ''),(100016744, 1, 'Spend points', ''),(100016745, 1, 'FAQ', ''),(100016746, 1, 'Points Frequently Asked Questions', ''),(100016747, 1, 'If you need help, the answer to your question is likely to be found on this page.', ''),(100016748, 1, 'Earning Points', ''),(100016749, 1, 'How do i earn points?', ''),(100016750, 1, 'You can earn points doing various activities on the site, like <a href=\'invite.php\'>referring friends</a>, uploading your <a href=\'user_editprofile_photo.php\'>profile photo</a>, creating groups, etc. You can also earn points by participating in our <a href=\'user_points_offers.php\'>offers</a>.', ''),(100016751, 1, 'What activities reward me with points?', ''),(100016752, 1, 'The following table lists activities and points awarded. Activities can have accumulation limits which are reset after \"Reset Period\"', ''),(100016753, 1, 'Activity', ''),(100016754, 1, 'Points', ''),(100016755, 1, 'Maximum', ''),(100016756, 1, 'Reset period', ''),(100016757, 1, 'day(s)', ''),(100016758, 1, 'Unlimited', ''),(100016759, 1, 'Never', ''),(100016760, 1, 'Spending Points', ''),(100016761, 1, 'How can i spend points?', ''),(100016762, 1, 'Check out our <a href=\'user_points_shop.php\'>points shop </a> where you can find various ways to spend your points.', ''),(100016763, 1, 'My Vault', ''),(100016764, 1, 'Transaction history', ''),(100016765, 1, 'Earn points', ''),(100016766, 1, 'Spend points', ''),(100016767, 1, 'FAQ', ''),(100016768, 1, 'Last Page', ''),(100016769, 1, 'showing offers', ''),(100016770, 1, 'of', ''),(100016771, 1, 'showing offers', ''),(100016772, 1, 'Next Page', ''),(100016773, 1, 'Offers', ''),(100016774, 1, 'Here you can see how to earn points.', ''),(100016775, 1, 'Search all offers', ''),(100016776, 1, 'Posted on', ''),(100016777, 1, 'views', ''),(100016778, 1, 'comments', ''),(100016779, 1, 'Tag Cloud', ''),(100016780, 1, 'No entries matched your search term.', ''),(100016781, 1, 'There are no available offers.', ''),(100016782, 1, 'My Vault', ''),(100016783, 1, 'Transaction history', ''),(100016784, 1, 'Earn points', ''),(100016785, 1, 'Spend points', ''),(100016786, 1, 'FAQ', ''),(100016787, 1, 'Last Page', ''),(100016788, 1, 'showing offers', ''),(100016789, 1, 'of', ''),(100016790, 1, 'showing offers', ''),(100016791, 1, 'Next Page', ''),(100016792, 1, 'Shop', ''),(100016793, 1, 'Here you can find ways to spend your hard earned points.', ''),(100016794, 1, 'Search all offers', ''),(100016795, 1, 'Posted on', ''),(100016796, 1, 'views', ''),(100016797, 1, 'comments', ''),(100016798, 1, 'Tag Cloud', ''),(100016799, 1, 'No entries matched your search term.', ''),(100016800, 1, 'There are no available offers.', ''),(100016801, 1, 'Please choose your classified:', ''),(100016802, 1, 'Please choose your event:', ''),(100016803, 1, 'Please choose your group:', ''),(100016804, 1, 'Please choose your poll:', ''),(100016805, 1, 'Continue', ''),(100016806, 1, 'Our All Time Stars', ''),(100016807, 1, 'Total points earned:', ''),(100016808, 1, 'A guide to stardom', ''),(100016809, 1, 'You can earn points by', ''),(100016810, 1, 'Uploading photos', ''),(100016811, 1, 'Making comments (don\'t spam as you will get punished and have points deducted)', ''),(100016812, 1, 'Inviting your friends', ''),(100016813, 1, 'Creating groups', ''),(100016814, 1, '(spam control in effect)', ''),(100016815, 1, 'Tagging your friends on the pictures', ''),(100016816, 1, 'See more', ''),(100016817, 1, 'Top Users', ''),(100016818, 1, 'points', ''),(100016819, 1, 'Nobody climbed to the top, yet.', ''),(100016820, 1, 'All users', ''),(100016821, 1, 'All users on level...', ''),(100016822, 1, 'All users in subnetwork...', ''),(100016823, 1, 'Specific user', ''),(100016824, 1, 'Level:', ''),(100016825, 1, 'Subnetwork:', ''),(100016826, 1, 'User:', ''),(100016827, 1, 'Amount:', ''),(100016828, 1, 'Set points (This will set points to specified amount instead of adding them)', ''),(100016829, 1, 'Add', ''),(100016830, 1, 'Add', ''),(100016831, 1, 'Price:', ''),(100016832, 1, 'Add', ''),(100016833, 1, 'Add', ''),(100016834, 1, 'All', ''),(100016835, 1, 'Edit User', ''),(100016836, 1, 'Activity Statistics', ''),(100016837, 1, 'Transactions', ''),(100016838, 1, 'Quotas', ''),(100016839, 1, 'Back to users', ''),(100016840, 1, 'Never', ''),(100016841, 1, 'Edit User', ''),(100016842, 1, 'Activity Statistics', ''),(100016843, 1, 'Transactions', ''),(100016844, 1, 'Quotas', ''),(100016845, 1, 'Back to users', ''),(100016846, 1, 'Edit User', ''),(100016847, 1, 'Activity Statistics', ''),(100016848, 1, 'Transactions', ''),(100016849, 1, 'Quotas', ''),(100016850, 1, 'Back to users', ''),(100016851, 1, 'All', ''),(100016852, 1, 'Edit User', ''),(100016853, 1, 'Activity Statistics', ''),(100016854, 1, 'Transactions', ''),(100016855, 1, 'Quotas', ''),(100016856, 1, 'Back to users', ''),(100016857, 1, 'Points:', ''),(100016858, 1, 'Rank:', ''),(100016859, 1, 'Profile Views:', ''),(100016860, 1, 'View', ''),(100016861, 1, '\'s Profile', ''),(100016862, 1, 'Read', ''),(100016863, 1, '\'s Blog', ''),(100016864, 1, 'From User', ''),(100016865, 1, 'Other', ''),(100016866, 1, 'Please specify user ID or username:', ''),(100016867, 1, 'Note: The \"From User\" will receive points, but will not receive the message (from himself).', '')");

  //######### INSERT ACTIONS
  $database->database_query("REPLACE INTO `se_actiontypes` (`actiontype_name`, `actiontype_icon`, `actiontype_setting`, `actiontype_enabled`, `actiontype_desc`, `actiontype_text`, `actiontype_vars`, `actiontype_media`) VALUES ('upearnercomment', 'action_postcomment.gif', '1', '1', '100016014', '100016015', '[username1],[displayname1],[offerid],[offertitle],[comment]', '0' ),('upspendercomment', 'action_postcomment.gif', '1', '1', '100016016', '100016017', '[username1],[displayname1],[offerid],[offertitle],[comment]', '0' )");






  //######### CREATE DATABASE STRUCTURE

  if(!function_exists('chain_sql')) {
    function chain_sql( $sql ) {
      global $database;

      $rows = explode( ';', $sql);
      foreach($rows as $row) {
        $row = trim($row);
        if(empty($row))
          continue;
        $database->database_query( $row );
      }

    }
  }

  chain_sql(
<<<EOC

# se_semods_actionpoints

CREATE TABLE IF NOT EXISTS `se_semods_actionpoints` (
  `action_id` int(11) NOT NULL auto_increment,
  `action_type` varchar(50) NOT NULL,
  `action_enabled` tinyint(1) NOT NULL default '1',
  `action_name` varchar(255) NOT NULL,
  `action_points` int(11) NOT NULL,
  `action_pointsmax` int(11) NOT NULL default '0',
  `action_rolloverperiod` int(11) NOT NULL default '0',
  `action_requiredplugin` varchar(100) default NULL,
  `action_group` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`action_id`),
  KEY `action_type` (`action_type`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


# CUSTOM ACTIONS
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (1, 'transferpoints', 1, 'Transfer Points', 1, 0, 0, NULL, 0);


# GENERAL ACTIONS
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (101, 'invite', 1, 'Invite Friends (for each invited friend)', 1, 0, 0, NULL, 101);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (102, 'refer', 1, 'Refer friends (actual signup)', 100, 0, 0, NULL, 101);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (103, 'signup', 1, 'Signup Bonus', 500, 0, 0, NULL, 101);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (104, 'addfriend', 1, 'Add a friend', 1, 10, 86400, NULL, 100);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (106, 'editphoto', 1, 'Upload profile photo', 100, 200, 86400, NULL, 100);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (107, 'editprofile', 1, 'Edit / Update profile', 10, 100, 86400, NULL, 100);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (108, 'editstatus', 1, 'Update status', 1, 50, 86400, NULL, 100);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (109, 'login', 1, 'Login to site (requires logout)', 1, 10, 86400, NULL, 100);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (110, 'adclick', 1, 'Clicking on an ad', 100, 1000, 86400, NULL, 100);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (111, 'newevent', 1, 'Create new event', 100, 0, 0, 'SupremeEdition Events plugin', 3);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (112, 'attendevent', 1, 'RSVP to Event', 1, 0, 0, 'SupremeEdition Events plugin', 3);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (113, 'eventcomment', 1, 'Comment on event', 10, 0, 0, 'SupremeEdition Events plugin', 3);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (114, 'eventmediacomment', 1, 'Comment on event photo', 10, 0, 0, 'SupremeEdition Events plugin', 3);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (115, 'postblog', 1, 'Post a blog', 1, 0, 0, 'SupremeEdition Blogs plugin', 5);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (116, 'blogcomment', 1, 'Comment on Blog', 1, 0, 0, 'SupremeEdition Blogs plugin', 5);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (117, 'postclassified', 1, 'Create a classified', 100, 1000, 86400, 'SupremeEdition Classifieds plugin', 4);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (118, 'classifiedcomment', 1, 'Comment on classified', 10, 100, 86400, 'SupremeEdition Classifieds plugin', 4);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (119, 'newalbum', 1, 'Upload an album', 100, 1000, 86400, 'SupremeEdition Photos plugin', 6);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (120, 'newmedia', 1, 'Upload new photo / media to album', 100, 1000, 86400, 'SupremeEdition Photos plugin', 6);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (121, 'mediacomment', 1, 'Comment on photo / media', 10, 100, 86400, 'SupremeEdition Photos plugin', 6);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (122, 'newgroup', 1, 'Create new group', 100, 500, 86400, 'SupremeEdition Groups plugin', 1);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (123, 'joingroup', 1, 'Join a group', 50, 200, 86400, 'SupremeEdition Groups plugin', 1);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (124, 'leavegroup', 1, 'Leave a group', 0, 0, 86400, 'SupremeEdition Groups plugin', 1);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (125, 'groupcomment', 1, 'Comment on group', 10, 100, 86400, 'SupremeEdition Groups plugin', 1);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (126, 'groupmediacomment', 1, 'Comment on group photo', 10, 100, 86400, 'SupremeEdition Groups plugin', 1);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (127, 'newpoll', 1, 'Create a poll', 0, 0, 0, 'SupremeEdition Polls plugin', 2);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (128, 'votepoll', 1, 'Participate in poll', 200, 0, 0, 'SupremeEdition Polls plugin', 2);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (129, 'pollcomment', 1, 'Commenting on a Poll', 10, 100, 86400, 'SupremeEdition Polls plugin', 2);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (130, 'newtag', 1, 'Getting Tagged in a Photo', 100, 1000, 86400, 'SupremeEdition Photos plugin', 6);
INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (131, 'newmusic', 1, 'Adding a Song', 100, 1000, 86400, 'SupremeEdition Music plugin', 9);



CREATE TABLE IF NOT EXISTS `se_semods_userpointcounters` (
  `userpointcounters_user_id` int(11) NOT NULL,
  `userpointcounters_action_id` int(11) NOT NULL,
  `userpointcounters_lastrollover` int(4) NOT NULL default '0',
  `userpointcounters_amount` int(11) NOT NULL default '0',
  `userpointcounters_cumulative` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userpointcounters_user_id`,`userpointcounters_action_id`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `se_semods_userpointstats` (
  `userpointstat_id` int(9) NOT NULL auto_increment,
  `userpointstat_user_id` int(11) NOT NULL,
  `userpointstat_date` int(9) NOT NULL default '0',
  `userpointstat_earn` int(9) NOT NULL default '0',
  `userpointstat_spend` int(9) NOT NULL default '0',
  PRIMARY KEY  (`userpointstat_id`),
  UNIQUE KEY `userpointstat_user_id` (`userpointstat_user_id`,`userpointstat_date`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `se_userpointearnercomments` (
  `userpointearnercomment_id` int(9) NOT NULL auto_increment,
  `userpointearnercomment_userpointearner_id` int(9) NOT NULL default '0',
  `userpointearnercomment_authoruser_id` int(9) NOT NULL default '0',
  `userpointearnercomment_date` int(14) NOT NULL default '0',
  `userpointearnercomment_body` text,
  PRIMARY KEY  (`userpointearnercomment_id`),
  KEY `INDEX` (`userpointearnercomment_userpointearner_id`,`userpointearnercomment_authoruser_id`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `se_userpointspendercomments` (
  `userpointspendercomment_id` int(9) NOT NULL auto_increment,
  `userpointspendercomment_userpointspender_id` int(9) NOT NULL default '0',
  `userpointspendercomment_authoruser_id` int(9) NOT NULL default '0',
  `userpointspendercomment_date` int(14) NOT NULL default '0',
  `userpointspendercomment_body` text,
  PRIMARY KEY  (`userpointspendercomment_id`),
  KEY `INDEX` (`userpointspendercomment_userpointspender_id`,`userpointspendercomment_authoruser_id`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `se_semods_uptransactions` (
  `uptransaction_id` int(11) NOT NULL auto_increment,
  `uptransaction_user_id` int(11) NOT NULL,
  `uptransaction_type` int(11) NOT NULL,
  `uptransaction_cat` int(11) NOT NULL default '0',
  `uptransaction_state` tinyint(4) NOT NULL,
  `uptransaction_text` text,
  `uptransaction_date` int(11) NOT NULL,
  `uptransaction_amount` int(11) NOT NULL,
  PRIMARY KEY  (`uptransaction_id`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `se_semods_userpointearnertypes` (
  `userpointearnertype_id` int(11) NOT NULL auto_increment,
  `userpointearnertype_type` int(11) NOT NULL,
  `userpointearnertype_typename` varchar(50) NOT NULL,
  `userpointearnertype_name` varchar(20) NOT NULL,
  `userpointearnertype_title` varchar(255) NOT NULL,
  PRIMARY KEY  (`userpointearnertype_id`),
  UNIQUE KEY `userpointearnertype_type` (`userpointearnertype_type`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


INSERT IGNORE INTO `se_semods_userpointearnertypes` (`userpointearnertype_type`, `userpointearnertype_typename`, `userpointearnertype_name`, `userpointearnertype_title`) VALUES (100, 'Affiliate', 'affiliate', 'Affiliate');
INSERT IGNORE INTO `se_semods_userpointearnertypes` (`userpointearnertype_type`, `userpointearnertype_typename`, `userpointearnertype_name`, `userpointearnertype_title`) VALUES (300, 'Poll vote', 'votepoll', 'Poll vote');
INSERT IGNORE INTO `se_semods_userpointearnertypes` (`userpointearnertype_type`, `userpointearnertype_typename`, `userpointearnertype_name`, `userpointearnertype_title`) VALUES (400, 'Generic', 'generic', 'Generic');



CREATE TABLE IF NOT EXISTS `se_semods_userpointranks` (
  `userpointrank_id` int(11) NOT NULL auto_increment,
  `userpointrank_amount` int(11) NOT NULL,
  `userpointrank_text` varchar(100) NOT NULL,
  PRIMARY KEY  (`userpointrank_id`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


INSERT IGNORE INTO `se_semods_userpointranks` (`userpointrank_id`, `userpointrank_amount`, `userpointrank_text`) VALUES (1, 0, 'Rookie');
INSERT IGNORE INTO `se_semods_userpointranks` (`userpointrank_id`, `userpointrank_amount`, `userpointrank_text`) VALUES (2, 500, 'Lieutenant');
INSERT IGNORE INTO `se_semods_userpointranks` (`userpointrank_id`, `userpointrank_amount`, `userpointrank_text`) VALUES (3, 1000, 'Member');
INSERT IGNORE INTO `se_semods_userpointranks` (`userpointrank_id`, `userpointrank_amount`, `userpointrank_text`) VALUES (4, 2000, 'Advanced Member');
INSERT IGNORE INTO `se_semods_userpointranks` (`userpointrank_id`, `userpointrank_amount`, `userpointrank_text`) VALUES (5, 10000, 'King');
INSERT IGNORE INTO `se_semods_userpointranks` (`userpointrank_id`, `userpointrank_amount`, `userpointrank_text`) VALUES (6, 100000, 'Impossible');



CREATE TABLE IF NOT EXISTS `se_semods_userpoints` (
  `userpoints_user_id` int(11) NOT NULL,
  `userpoints_count` int(11) NOT NULL default '0',
  `userpoints_totalearned` int(11) NOT NULL default '0',
  `userpoints_totalspent` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userpoints_user_id`),
  KEY `userpoints_totalearned` (`userpoints_totalearned`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;




CREATE TABLE IF NOT EXISTS `se_semods_userpointspendertypes` (
  `userpointspendertype_id` int(11) NOT NULL auto_increment,
  `userpointspendertype_type` int(11) NOT NULL,
  `userpointspendertype_typename` varchar(50) NOT NULL,
  `userpointspendertype_name` varchar(20) NOT NULL,
  `userpointspendertype_title` varchar(255) NOT NULL,
  PRIMARY KEY  (`userpointspendertype_id`),
  UNIQUE KEY `userpointspendertype_type` (`userpointspendertype_type`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (1, 'Post a Classified listing', 'charge', 'Posting a Classified listing');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (2, 'Create an Event', 'charge', 'Creating an Event');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (3, 'Create a Group', 'charge', 'Creating a Group');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (4, 'Create a Poll', 'charge', 'Creating a Poll');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (100, 'Profile Promotion', 'promote', 'Promote a Profile');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (101, 'Classified Promotion', 'promote', 'Promote a Classified');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (102, 'Event Promotion', 'promote', 'Promote an Event');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (103, 'Group Promotion', 'promote', 'Promote a Group');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (104, 'Poll Promotion', 'promote', 'Promote a Poll');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (200, 'Level Upgrade', 'levelupgrade', 'Level Upgrade');
INSERT IGNORE INTO `se_semods_userpointspendertypes` (`userpointspendertype_type`, `userpointspendertype_typename`, `userpointspendertype_name`, `userpointspendertype_title`) VALUES (400, 'Generic', 'generic', 'Generic');



CREATE TABLE IF NOT EXISTS `se_semods_userpointspender` (
  `userpointspender_id` int(11) NOT NULL auto_increment,
  `userpointspender_type` int(4) NOT NULL default '0',
  `userpointspender_name` varchar(100) NOT NULL,
  `userpointspender_title` varchar(255) NOT NULL,
  `userpointspender_body` text NOT NULL,
  `userpointspender_date` int(4) NOT NULL default '0',
  `userpointspender_photo` varchar(10) default NULL,
  `userpointspender_cost` int(11) NOT NULL default '0',
  `userpointspender_views` int(11) NOT NULL default '0',
  `userpointspender_comments` int(11) NOT NULL default '0',
  `userpointspender_comments_allowed` tinyint(1) NOT NULL default '1',
  `userpointspender_enabled` tinyint(1) NOT NULL default '1',
  `userpointspender_transact_state` int(11) NOT NULL default '0',
  `userpointspender_metadata` text NOT NULL,
  `userpointspender_redirect_on_buy` varchar(255) default NULL,
  `userpointspender_tags` varchar(255) default NULL,
  `userpointspender_engagements` int(11) NOT NULL default '0',
  `userpointspender_levels` text,
  `userpointspender_subnets` text,
  PRIMARY KEY  (`userpointspender_id`),
  KEY `userpointspender_type` (`userpointspender_type`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


INSERT IGNORE INTO `se_semods_userpointspender` (`userpointspender_id`, `userpointspender_type`, `userpointspender_name`, `userpointspender_title`, `userpointspender_body`, `userpointspender_date`, `userpointspender_photo`, `userpointspender_cost`, `userpointspender_views`, `userpointspender_comments`, `userpointspender_comments_allowed`, `userpointspender_enabled`, `userpointspender_transact_state`, `userpointspender_metadata`, `userpointspender_redirect_on_buy`, `userpointspender_tags`, `userpointspender_engagements`, `userpointspender_levels`, `userpointspender_subnets`) VALUES (1, 1, '', 'Post a Classified listing', 'Post a Classified listing', 0, '', 1, 0, 0, 1, 1, 0, '', NULL, NULL, 0, NULL, NULL);
INSERT IGNORE INTO `se_semods_userpointspender` (`userpointspender_id`, `userpointspender_type`, `userpointspender_name`, `userpointspender_title`, `userpointspender_body`, `userpointspender_date`, `userpointspender_photo`, `userpointspender_cost`, `userpointspender_views`, `userpointspender_comments`, `userpointspender_comments_allowed`, `userpointspender_enabled`, `userpointspender_transact_state`, `userpointspender_metadata`, `userpointspender_redirect_on_buy`, `userpointspender_tags`, `userpointspender_engagements`, `userpointspender_levels`, `userpointspender_subnets`) VALUES (2, 2, '', 'Create an Event', 'Create an Event', 0, '', 1, 0, 0, 1, 1, 0, '', NULL, NULL, 0, NULL, NULL);
INSERT IGNORE INTO `se_semods_userpointspender` (`userpointspender_id`, `userpointspender_type`, `userpointspender_name`, `userpointspender_title`, `userpointspender_body`, `userpointspender_date`, `userpointspender_photo`, `userpointspender_cost`, `userpointspender_views`, `userpointspender_comments`, `userpointspender_comments_allowed`, `userpointspender_enabled`, `userpointspender_transact_state`, `userpointspender_metadata`, `userpointspender_redirect_on_buy`, `userpointspender_tags`, `userpointspender_engagements`, `userpointspender_levels`, `userpointspender_subnets`) VALUES (3, 3, '', 'Create a Group', 'Create a Group', 0, '', 1, 0, 0, 1, 1, 0, '', NULL, NULL, 0, NULL, NULL);
INSERT IGNORE INTO `se_semods_userpointspender` (`userpointspender_id`, `userpointspender_type`, `userpointspender_name`, `userpointspender_title`, `userpointspender_body`, `userpointspender_date`, `userpointspender_photo`, `userpointspender_cost`, `userpointspender_views`, `userpointspender_comments`, `userpointspender_comments_allowed`, `userpointspender_enabled`, `userpointspender_transact_state`, `userpointspender_metadata`, `userpointspender_redirect_on_buy`, `userpointspender_tags`, `userpointspender_engagements`, `userpointspender_levels`, `userpointspender_subnets`) VALUES (4, 4, '', 'Create a Poll', 'Create a Poll', 0, '', 1, 0, 0, 1, 1, 0, '', NULL, NULL, 0, NULL, NULL);






CREATE TABLE IF NOT EXISTS `se_semods_userpointearner` (
  `userpointearner_id` int(11) NOT NULL auto_increment,
  `userpointearner_type` int(4) NOT NULL default '0',
  `userpointearner_name` varchar(100) NOT NULL,
  `userpointearner_title` varchar(255) NOT NULL,
  `userpointearner_body` text NOT NULL,
  `userpointearner_date` int(4) NOT NULL default '0',
  `userpointearner_photo` varchar(10) default NULL,
  `userpointearner_cost` int(11) NOT NULL default '0',
  `userpointearner_views` int(11) NOT NULL default '0',
  `userpointearner_comments` int(11) NOT NULL default '0',
  `userpointearner_comments_allowed` tinyint(1) NOT NULL default '1',
  `userpointearner_enabled` tinyint(1) NOT NULL default '1',
  `userpointearner_transact_state` int(11) NOT NULL default '0',
  `userpointearner_metadata` text NOT NULL,
  `userpointearner_redirect_on_buy` varchar(255) default NULL,
  `userpointearner_tags` varchar(255) default NULL,
  `userpointearner_field1` int(11) NOT NULL default '0',
  `userpointearner_engagements` int(11) NOT NULL default '0',
  `userpointearner_levels` text NOT NULL,
  `userpointearner_subnets` text NOT NULL,
  PRIMARY KEY  (`userpointearner_id`),
  KEY `userpointearner_field1` (`userpointearner_field1`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


# ALTER SE_ADS ENLARGE ad_name

ALTER TABLE `se_ads` CHANGE `ad_name` `ad_name` VARCHAR( 500 ) NOT NULL;


# PROMOTION TEMPLATE

INSERT IGNORE INTO `se_ads` (`ad_name`, `ad_date_start`, `ad_date_end`, `ad_paused`, `ad_limit_views`, `ad_limit_clicks`, `ad_limit_ctr`, `ad_public`, `ad_position`, `ad_levels`, `ad_subnets`, `ad_html`, `ad_total_views`, `ad_total_clicks`, `ad_filename`) VALUES ('PROMOTION TEMPLATE FOR PROFILE ON PAGETOP', '1199232000', '0', 1, 0, 0, '0', 1, 'top', ',1,', ',0,', 'HTML Template is specified on the offer edit page.', 0, 0, '');


# SYNC USERS TO POINTS TABLE
INSERT IGNORE INTO se_semods_userpoints (userpoints_user_id) (SELECT user_id FROM se_users);


EOC
);

  $database->database_query("INSERT IGNORE INTO `se_semods_userpointearner` (`userpointearner_type`, `userpointearner_name`, `userpointearner_title`, `userpointearner_body`, `userpointearner_date`, `userpointearner_photo`, `userpointearner_cost`, `userpointearner_views`, `userpointearner_comments`, `userpointearner_comments_allowed`, `userpointearner_enabled`, `userpointearner_transact_state`, `userpointearner_metadata`, `userpointearner_redirect_on_buy`, `userpointearner_tags`, `userpointearner_field1`, `userpointearner_engagements`, `userpointearner_levels`, `userpointearner_subnets`) VALUES (100, '', 'Visit SocialEngineMods', 'Visit SocialEngineMods Site for fun and profit.', 1212589472, NULL, 1000, 0, 0, 1, 1, 1, 'a:1:{s:3:\"url\";s:56:\"http://demo.socialenginemods.net/?custom=[transactionid]\";}', NULL, 'affiliate', 0, 1, '', '')");
  $database->database_query("INSERT IGNORE INTO `se_semods_actionpoints` (`action_id`, `action_type`, `action_enabled`, `action_name`, `action_points`, `action_pointsmax`, `action_rolloverperiod`, `action_requiredplugin`, `action_group`) VALUES (105, 'profilecomment', 1, 'Comment on someone&#039;s profile', 10, 100, 86400, NULL, 100)");



  //######### ADD COLUMNS/VALUES TO se_users TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'user_userpoints_allowed'")) == 0) {
    $database->database_query("ALTER TABLE se_users
					ADD COLUMN `user_userpoints_allowed` TINYINT( 1 ) NOT NULL DEFAULT '1'
	");

  }


  //######### USER LEVELS
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_userpoints_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels
                    ADD`level_userpoints_allow` tinyint(1) NOT NULL default '1',
                    ADD`level_userpoints_allow_transfer` tinyint(1) NOT NULL default '1',
                    ADD`level_userpoints_max_transfer` int(11) NOT NULL default '0';
	");
  }



  /*** SHARED ELEMENTS ***/



  //######### CREATE se_semods_settings
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_semods_settings'")) == 0) {

    $database->database_query("CREATE TABLE `se_semods_settings` (
		`setting_userpoints_charge_postclassified` tinyint(1) NOT NULL default '0',
		`setting_userpoints_charge_newevent` tinyint(1) NOT NULL default '0',
		`setting_userpoints_charge_newgroup` tinyint(1) NOT NULL default '0',
		`setting_userpoints_charge_newpoll` tinyint(1) NOT NULL default '0',
		`setting_userpoints_enable_offers` tinyint(1) NOT NULL default '1',
		`setting_userpoints_enable_shop` tinyint(1) NOT NULL default '1',
		`setting_userpoints_enable_topusers` tinyint(1) NOT NULL default '1',
		`setting_userpoints_enable_statistics` tinyint(1) NOT NULL default '1',
		`setting_userpoints_enable_pointrank` tinyint(1) NOT NULL default '1',
		`setting_userpoints_exchange_rate` int(11) NOT NULL default '1000'
	  )
	");

    $database->database_query("INSERT INTO `se_semods_settings` (`setting_userpoints_enable_offers`) VALUES (1)");

  } else {

    $database->database_query("ALTER TABLE `se_semods_settings`
		 ADD `setting_userpoints_charge_postclassified` tinyint(1) NOT NULL default '0',
		 ADD `setting_userpoints_charge_newevent` tinyint(1) NOT NULL default '0',
		 ADD `setting_userpoints_charge_newgroup` tinyint(1) NOT NULL default '0',
		 ADD `setting_userpoints_charge_newpoll` tinyint(1) NOT NULL default '0',
		 ADD `setting_userpoints_enable_offers` tinyint(1) NOT NULL default '1',
		 ADD `setting_userpoints_enable_shop` tinyint(1) NOT NULL default '1',
		 ADD `setting_userpoints_enable_topusers` tinyint(1) NOT NULL default '1',
		 ADD `setting_userpoints_enable_statistics` tinyint(1) NOT NULL default '1',
		 ADD `setting_userpoints_enable_pointrank` tinyint(1) NOT NULL default '1',
		 ADD `setting_userpoints_exchange_rate` int(11) NOT NULL default '1000'
	");

  }


}


?>