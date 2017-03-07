<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty.
Check COPYING file for more details.
Copyright (C) 2014 Paul Puzyrev. www.minibb.com
Latest File Update: 2014-Sep-28
*/

$DB='mysqli';

$DBhost='brumbrumqbbb2016.mysql.db';
$DBname='brumbrumqbbb2016';
$DBusr='brumbrumqbbb2016';
$DBpwd='Bb170516';

$Tf='bb2_fori';
$Tp='bb2_messaggi';
$Tt='bb2_discussioni';
$Tu='bb2_utenti';
$Ts='bb2_sendmails';
$Tb='bb2_bannati';

$admin_usr='God';
$admin_pwd='soviet2009';
$admin_email='god@brumbrum.lol';

$bb_admin='top_admin_script.php?';

$indexphp='index.php?';

$cookiedomain='';
$cookiename='brumbrum';
$cookiepath='';
$cookiesecure=FALSE;
$cookie_expires=108000;
$cookie_renew=1800;
$cookielang_exp=2592000;

$main_url='http://brumbrum.lol/forum3';

$lang='ita';
$skin='default';
//$sitename=(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'').' Forum';
$sitename='brumbrum &#9733;';
$emailadmin=0;
$emailusers=0;
$userRegName='_A-Za-z0-9 ';
$l_sepr='<span class="sepr">|</span>';

$post_text_maxlength=10240;
$post_word_maxlength=70;
$topic_max_length=1000;
$viewmaxtopic=30;
$viewlastdiscussions=20;
$viewmaxreplys=25;
$viewmaxsearch=50;
$viewpagelim=250;
$viewTopicsIfOnlyOneForum=0;

$protectWholeForum=0;
$protectWholeForumPwd='cius';

$postRange=5;

$dateOnlyFormat='j F Y';
$timeOnlyFormat='H:i';
$dateFormat=$dateOnlyFormat.' '.$timeOnlyFormat;



/* New options for miniBB 1.1 */

$disallowNames=array('Anonymous', 'Fuck', 'Shit', 'Guest');
$disallowNamesIndex=array('admin', 'guest'); // 2.0 RC1f

/* New options for miniBB 1.2 */
$sortingTopics=0;
$topStats=4;
$genEmailDisable=0;

/* New options for miniBB 1.3 */
$defDays=60;
$userUnlock=0;

/* New options for miniBB 1.5 */
$emailadmposts=0;
$useredit=1800;

/* New options for miniBB 1.6 */
//$metaLocation='go';
$closeRegister=1;
//$timeDiff=21600;

/* New options for miniBB 1.7 */
$stats_barWidthLim='31';

/* New options for miniBB 2.0 */

$dbUserSheme=array(
'username'=>array(1,'username','login'),
'user_password'=>array(3,'user_password','passwd'),
'user_email'=>array(4,'user_email','email'),
'user_icq'=>array(5,'user_icq','icq'),
'user_website'=>array(6,'user_website','website'),
'user_occ'=>array(7,'user_occ','occupation'),
'user_from'=>array(8,'user_from','from'),
'user_interest'=>array(9,'user_interest','interest'),
'user_viewemail'=>array(10,'user_viewemail','user_viewemail'),
'user_sorttopics'=>array(11,'user_sorttopics','user_sorttopics'),
'language'=>array(14,'language','language'),
'num_topics'=>array(16,'num_topics',''),
'num_posts'=>array(17,'num_posts',''),
'user_custom1'=>array(18,'user_custom1','user_custom1'),
'user_custom2'=>array(19,'user_custom2','user_custom2'),
'user_custom3'=>array(20,'user_custom3','user_custom3')
);
$dbUserId='user_id';
$dbUserDate='user_regdate'; $dbUserDateKey=2;
$dbUserAct='activity';
$dbUserNp='user_newpasswd';
$dbUserNk='user_newpwdkey';

$enableNewRegistrations=FALSE;
$enableProfileUpdate=TRUE;

$usersEditTopicTitle=TRUE;
$pathToFiles='./';
//$includeHeader='header.php';
//$includeFooter='footer.php';
//$emptySubscribe=TRUE;
$allForumsReg=TRUE;
//$registerInactiveUsers=TRUE;
//$mod_rewrite=TRUE;
$enableViews=TRUE;
$userInfoInPosts=array($dbUserSheme['user_custom1'][1]);
//$userDeleteMsgs=1;

$description='Lore e Leo sono orgogliosi di presentarvi: le vignette di stampela, magliette a gratis, discussioni varie amore, odio, sport, politica. Tutto il forum di brumbrum &egrave; contro il rombo ed &egrave; innamorato dei rutti e della voce di Booble666, la pace nel mondo &egrave; vicina';

$startIndex='index.php'; // or 'index.html' for mod_rewrite
$manualIndex='index.php?action=manual'; // or 'manual.html' for mod_rewrite

$enableGroupMsgDelete=TRUE;
$post_text_minlength=2;
$loginsCase=FALSE;

$allowHyperlinks=5;

$reply_to_email=$admin_email;

//$addMainTitle=1;

$startPageModern=TRUE;

?>