<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2011 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
?>


1. Copyright and disclaimer
---------------------------

This application is released under GNU/GPL License.
All Rights Reserved
Copyright (c) 2008-2011 - Bernard Gilly
ALPHAUSERPOINTS IS DISTRIBUTED "AS IS". NO WARRANTY OF ANY KIND IS EXPRESSED OR IMPLIED. YOU USE IT AT YOUR OWN RISK.
THE AUTHOR WILL NOT BE LIABLE FOR ANY DAMAGES, INCLUDING BUT NOT LIMITED TO DATA LOSS, LOSS OF PROFITS OR ANY OTHER KIND OF LOSS WHILE USING OR MISUSING THIS SCRIPT.


2. Changelog
------------

This is a non-exhaustive (but still near complete) changelog for
AlphaUserPoints, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.


3. Legend
---------

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

--------------------  1.5.13 stable [11 january 2011]  ---------------------
+ added function to insert raffle in editor for plg_editors-xtd_raffle 1.0
# fixed loading language for com_media
# fixed problem with Uddeim API
# fixed medals system on recalculation
+ added Greek language for frontend

--------------------  1.5.12 stable [10 November 2010]  ---------------------
+ added view for all activities in backend
# fixed recalculate and attribs medal on certain rules in backend
- removed adding unique index on userid during installation/upgrade
+ added new pre installed rule "Thank You" for Kunena forum 1.6.1
+ added new pre installed rule "Delete post" for Kunena forum 1.6.1
^ Migration old names of plugins/rules for Kunena -> Create Topic and Reply Topic
# fixed possibility to download full activity by a guest user from module
+ added russian help in backend
+ added new table #__alpha_userpoints_version (for future upgrade 1.5 to 1.6)

--------------------  1.5.11 stable [09 September 2010]  ---------------------
# fixed router.php
# fixed Uddeim integration
# fixed medals

--------------------  1.5.10 stable [28 August 2010]  ---------------------
# fixed link to profil in account view
# fixed import Jplugin in API
# fixed error on return value of new by reference is deprecated in controller backend line 464
# fixed Donate Points fields dissappear in IE
# fixed birthday on edit/save when format xx/xx/xxxx
# fixed show avatar from AlphaUserPoints even if set to none
# fixed problem in AlphaUserPointsHelper::getAupAvatar() function if called from backend
+ added OSE Membership Control system registration on invite
+ added arabic language for backend component

--------------------  1.5.9 stable [01 July 2010]  ---------------------
# fixed OpenInviter with SEF enabled
# fixed router.php

--------------------  1.5.8 stable [29 June 2010]  ---------------------
# fixed invite with success and IP in keyreference
# fixed Undefined index: gender on save profile
# fixed combine activities
# fixed rule invite to read an article with SEF enabled
# fixed url of article in rule Reader to Author
+ added option to hide referral user column in frontend users list
* security fix Local File Inclusion Vulnerability http://www.secunia.com/advisories/39250/
+ added Arabic language (ar-DZ) for frontend

--------------------  1.5.7 stable [15 June 2010]  ---------------------
* security fix Local File Inclusion Vulnerability http://www.secunia.com/advisories/39250/
# fixed getAupUsersURL() function in API
# fixed Undefined variable: percent in view Statistics
# fixed implementation with phpThumb and JomSocial

--------------------  1.5.6 stable [02 May 2010]  ---------------------
+ added Profile view members authorized for guest visitor (new parameter in backend configuration)
# fixed getAvatar function
+ added phpThumb integration for avatar and rank
# fixed url inserted in datareference (reader to author rule)
# fixed export CSV list members subscriptions to a raffle
+ added new raffle system: link for download as a prize
+ added multiple entries for a raffle (as a system tickets lottery)
+ added button to delete all activities in each user details
+ added export activities for each user (CSV format)
+ added export all activities for all users (CSV format)
+ added index on userid column in #__alpha_userpoints table
+ added show current points of the current user in article content (new content plugin and new content tag)
# fixed prevent subscription if raffle already made
# fixed insert date to new user registration rule
# fixed rank on upgrade or downgrade and recalculate auto on any changes in the rank manager
+ added combine activities: combine the set of all actions in one activity from a specified date (perform database)
* security fix Local File Inclusion Vulnerability http://www.secunia.com/advisories/39250/
+ added router.php for SEF on frontend
+ added show link if enabled to guest users on medals list
# fixed recount Num chars on field "About me" in the profile member if not empty
+ added possibility choose dates format for the birthdate (new param in the menu "Account"
+ added new rule "Donate to author", the reader gives points to the author to read his article with ability to customize the amount of points (it's a content plugin)
^ help files updated
^ change generic_gravatar_grey.gif to generic_gravatar_grey.png
+ added russian language backend

--------------------  1.5.5 stable [21 February 2010]  ---------------------
# fixed code to use class uddeim api
# fixed system cache to check update new version available
# fixed max daily points function
# fixed request to change user level
# fixed export email in backend (missing all emails with [at] instead @)
# fixed invite with success
# fixed insertion data reference on submit a weblink
+ added OpenInviter integration on invite layout
+ added possibility to delete a pending approval activity directly from control panel
+ added on send notification the reason of custom points rule
+ added new param in configuration to allows the integration of all activities, even if the activity is zero point
- removed global $mainframe

--------------------  1.5.4 stable [27 december 2009]  ---------------------
# fixed html code for class in latest activity (missing equal symbol)
# fixed timezone in Uddeim notification
# prevent error if Uddeim component is not installed
^ improve API function getAupAvatar() and add Itemid in url
+ added param class and other profile url in API function getAupAvatar()
+ added Romanian (ro-RO) language for frontend
^ upgrade language files spanish, brazilian and German

--------------------  1.5.3 stable [12 october 2009]  ---------------------
* security fix in ajax function to check username (donate points to other users)
# fixed save phone numbers on user profile
# fixed wrong time in latest activity
# fixed Avatar and profile automatically resets if some changes in backend
# fixed problem with limit daily points with some rules (e.g. coupons code)
# fixed error in plugin sysplgaup_newregistered line 161
# fixed recount referrees on delete user and delete the referral ID for other user
# fixed donate points only works once, now you can donate anymore
# fixed round year for determine age in profile
# fixed function make raffle with coupons code in backend
# fixed division by zero in profil (statistics)
# fixed rule Reader to Author with guest user
^ change process to install and publish plugins (system, users and content) on install
^ change process to uninstall plugins (system, users and content) on uninstall
^ change keyreference for the rule reader to author ( old key = id of article; new key = id of article + ":reader2author" + IP ). Now, IP of reader not show in activity
+ added allows html in description for custom rule in backend
+ added new tab in configuration component for integration third components (backend)
+ added integration Uddeim component
+ added choice to link to other profile provide by third components
+ added pre-installed rules for Kunena
+ added caching system to check current version
+ added Dutch (nl-NL) language in backend


--------------------  1.5.2 stable [03 august 2009]  ---------------------
^ format raffle date format (DATE_FORMAT_LC2) in raffle list (admin)
^ change coupon code point(admin): display list of user awarded per coupon
^ change lenght 'referreid' field in tables for better compliance with user field lenght of Joomla! (150 chars)
# fixed recalculate points function on user when remove his action(s)
# fixed display coupon code in activity
# fixed Notice: Undefined variable: referrerid in currentrequests.php on line 48
# fixed Notice: Use of undefined constant AUP_ACCEPT - assumed 'AUP_ACCEPT' in /views/cpanel/tmpl/default.php on line 397 (admin)
# fixed Notice: Use of undefined constant AUP_REJECT - assumed 'AUP_REJECT' in /views/cpanel/tmpl/default.php on line 398 (admin)
# fixed Notice: line 555 in helper.php on recalculate function
# fixed block negative points on user to user points rule
# fixed error on statistic TOP 10 in user profile tab
# fixed showing data reference for owner coupon
# fixed operation aborted with reCaptcha and IE7
+ added tab to display coupons code points used in user profil
+ added categories for rules
+ added new profile fields
+ added progress profile complete
+ added use avatar from CBE
+ added new rule for upload avatar/photo
+ added new rule for profile complete
+ added new rule for inactive users
+ added new statistics in backend 
+ added Czech language (frontend)


--------------------  1.5.1 stable [07 july 2009]  ---------------------
^ changed notification message : add rule name for action
# fixed division by zero on statistics user fontend
# fixed error in SQL syntax on AlphaUserPointsHelper::checkRankMedal()
# fixed time offset in raffle proceed
# fixed display only one message of congratulation on sending multiple invites with total points earned
# fixed {AUP::CONTENT=XX} on article showing by guest user
# fixed uninstall plugins and admin module before upgrade
# fixed language for upload image in level/rank (backend)
# fixed Medals List layout menuitem type show Users List layout menuitem type in Medals List top
+ added show/hide referrer ID column on users list (frontend)
+ added label 'Rank' column on users list (frontend)
+ added missing var 'AUP_RAFFLE' in frontend languages

--------------------  1.5.0 stable [22 june 2009]  ---------------------
Backend :
---------
# fixed Notice: Undefined offset: 0 on user details if no activity
# improved users synchronization  (support huge site)
# improved users recalculation points (support huge site)
+ added icon on administrator backend's Control Panel page. The icon displays a warning site if there are pending approval points
+ hide automatically the Current requests for change level on control panel of AUP if rules change level disabled
+ added possibility to approve all (or selected) pending approval points directly from control panel of AUP
+ added upload image directly in ranks/medals manager
+ added coupon generator in coupon codes manager (toolbar)
+ added 10 Latest activities on control panel
+ added new rule for specific content based on "onPrepareContent" of content plugin of Joomla
+ added avatar from AlphaUserPoints

Frontend :
----------
^ distinct controllers
# fixed rule birthday
# fixed Notice: Undefined index: referrerid in /plugins/user/sysplgaup_newregistered.php on line 41
# fixed Notice: Undefined variable: changelevel1 in /components/com_alphauserpoints/controller.php on line 176
# fixed bad statistics on frontend for other users
# fixed Exclude Users on statistics
# fixed display RSS activity
# fixed rank/medals
# fixed date format on frontend
# fixed ordering medals on menu medals
# fixed problem script with reCaptcha and IE
# prevent loading reCaptcha libray if already loaded
+ added show/hide tab statistics in profil/account
+ added show/hide total community points
+ added show/hide percentage community points
+ added show/hide top 10 in statistics profil
+ added show/hide links to other profil users
+ added (show/hide) link to profil on frontend users list
+ added (show/hide) avatar on frontend users list
+ added send email to admin on each request to change user level
+ added possibility to set a number item for latest activities in profil or display full activity with pagination
+ added Jomsocial into system registration of invite menu
+ added RSS activity menu on frontend
+ added (show/hide) link to profil on medal users listing
+ added (show/hide) avatar on medal users listing
+ added new menu for showing latest activity
+ added JQuery to checking username validation on form to donate points to another user
+ added upload avatar in user profil/account (avatar from AlphaUserPoints)

--------------------  1.4.0 stable [04 june 2009]  ---------------------
# fixed var language for AUP_CUSTOM in frontend
# fixed return in helper.php
+ added purge automatically old users not reliable to #__users table
+ added ordering on medals/ranks
+ added possibility to attachment at a rule for the medals/ranks
+ added check version update in backend (hide/show in general params)
+ added download link of full activity for current user (profile/account)
+ added show avatar from other profils components (account/profil)
+ added new tab in account/profil to swhowing referrees
+ added settings in profil user
+ added profil can view by other members
+ added new rule "birthday"
^ user profil/account modified
^ statistics user improved in profil/account user
+ added Brazilian language (frontend)

--------------------  1.3.2 stable [22 may 2009]  ---------------------
+ added custom points rules
^ possibility to limit the number of points donation to another user
^ rule donation to another user now is limited once per day
+ added new tab in account/profil to swhowing statistics
^ help file modified
# fixed feedback on API (return true or false)

--------------------  1.3.1 stable [27 april 2009]  -------------------
# fixed Undefined index on referreid
# fixed Undefined variable filterlevelrank (backend)
# fixed problem on Buy Points with Paypal and IE
+ added upload multiple rules in zip

--------------------  1.3.0 stable [04 april 2009]  -------------------
+ invitation compliance with Joomsuite User
+ new parameter to constrain access rule in API
+ rank and medals system for members (both can be combined)
^ user help file
# fixed reCaptcha security if blank

--------------------  1.2.0 stable [05 march 2009]  -------------------
+ added invitation compliance with Joomunity
+ added invitation compliance with VirtueMart
+ added tacking referrals with a cookie/session for new registered
# fixed error on check key reference if used rule not published
# fixed error with PHP4 and function JFactory::getDate()->toFormat (only available with PHP5)
# fixed error after search details user -> no result
# fixed error in backend after manual approve an action -> if Referral points rule enabled, not point assigned to referral user
^ improving the rule "Read article", now possibility to negative points (like paid to read)
^ improving the rules "Read article" and "Reader to author", now an author can't earn point on its own articles
+ added rss feed to global activity (module mod_alphauserpoints_rss_activity must be installed)
+ added Referral invitation link in the form to invite (invite/recommend menu)
+ added export list users registration to a raffle (csv format)
+ added German language
+ added Dutch language (frontend)
+ added Russian language (frontend)

--------------------  1.1.0 stable [24 december 2008]  -------------------
* Prevent multi registered with same ip (Ip tracking on invite with success)
+ added daily login rule
+ added invitation available with CBE (CBE must be modified)
+ added set max daily points (in backend configuration)
+ added Raffle system
^ upgrade version system

--------------------  1.0.0 stable [26 October 2008]  --------------------
# fixed function check rule in helper.php
# fixed navigation in details user (blank page)
+ added rules select list in statistics date to date
+ added new system rule "Read article"
+ added new system rule "Vote article"
+ added new system rule "Click banner"
+ added configuration in backend
+ added possibility public or private coupon code (Using with coupon module v.2)
+ added possibility use native Identifier AUP or use username (in configuration)
- removed _ALPHAUSERPOINTS_WARNING_CONGRATULATION in helper.php (move in configuration component)
- removed prefix identifier in settings plugin User - AlphaUserPoints (move in configuration component)

--------------------  1.0.0 RC 2 [13 October 2008]  --------------------
# fixed messages on e-mail notification
# fixed french language file with no BOM tag

--------------------  1.0.0 RC 1 [01 October 2008]  --------------------
# fixed error on var menuname in view/buypoints
# fixed var userid and title in controller for the task buy points
# fixed table for content title and description article in default view (buy points)
+ added message on frontend for negative points
+ added confirm before reset all points in backend
+ added coupon codes system for points
+ added statistics date to date

--------------------  1.0.0 Release Candidate [24 September 2008]  --------------------
# fixed approved points in backend
# fixed params tag in plugin for new registered
+ added system and new menu for buy points with Paypal (new rule added)
+ added Referral ID in account page (frontend)
^ Editing and adding in the help file

--------------------  0.9.14 beta release [20 September 2008]  --------------------
# fixed message on current user if point assign to other member
# fixed install rule by xml
+ added possibility to show/hide Name column on user list (frontend)

--------------------  0.9.13 beta release [16 September 2008]  --------------------
# fixed conflicts with QContacts component and Captcha image
+ added choice system registration Joomla Core/Community Builder on settings invite menu

--------------------  0.9.12 beta release [15 September 2008]  --------------------
# fixed navigation on rules page
# fixed navigation on users statistics page
# fixed navigation on details user
# fixed error on API newpoints() in help file (english only)
+ added sort on ID in Users Statistics

--------------------  0.9.11 beta release [12 September 2008]  --------------------
! -> First release of this new component
