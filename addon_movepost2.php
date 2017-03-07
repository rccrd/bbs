<?php
/*
addon_movepost2.php : administration file for miniBB 2.
This file is part of miniBB. miniBB is free discussion forums/message board software, provided with no warranties.
See COPYING file for more details.
Copyright (C) 2014,2015 Paul Puzyrev. www.minibb.com
Latest File Update: 2015-Dec-27
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

$fName=$pathToFiles.'lang/movepost_'.$lang.'.php';
if(file_exists($fName)) include($fName); else include($pathToFiles.'lang/movepost_eng.php');
if($action=='vthread' or $action=='delAvatarAdmin') return;
$userStats=FALSE;

if($user_id==1 or $isMod==1){

$title.=$l_movePost;
echo load_header();

echo '<table class="forumsmb"><tr><td>';

if(isset($_POST['step']) and (int)$_POST['step']==2){

$topicid=(isset($_POST['topicid'])?(integer)$_POST['topicid']+0:0);
$postid=(isset($_POST['postid'])?(integer)$_POST['postid']+0:0);
$topictitle=(isset($_POST['topictitle'])?trim($_POST['topictitle']):'');
$forum_id=(isset($_POST['forum_id'])?(integer)$_POST['forum_id']+0:0);
$movedMsgs=(isset($_POST['movedMsgs'])?explode(',',$_POST['movedMsgs']):array($postid));

$checkPost=$movedMsgs[0];

$err1=($forum_id!=0 and $checkPost!=0 and $rs=db_simpleSelect(0,$Tp,'topic_id, poster_id, poster_name, post_time, poster_ip','post_id','=',$checkPost));

/* determine old page */
$oldtopicid=$rs[0];
if(isset($themeDesc) and in_array($oldtopicid,$themeDesc)) $vv=TRUE; else $vv=FALSE;
if(!$vv) $sg='<'; else $sg='>';
if($row=db_simpleSelect(0, $Tp, 'count(*)', 'post_id', $sg, $checkPost, '', '', 'topic_id', '=', $oldtopicid)) $pt=$row[0]; else $pt=0;
if($pt<=$viewmaxreplys) $oldPage=PAGE1_OFFSET+1; else $oldPage=(integer)($pt/$viewmaxreplys)+PAGE1_OFFSET+1;
$oldPage=pageChk($oldPage, $pt, $viewmaxreplys);

$origPosterId=$rs[1];

if($topictitle!='' and !$err1){
$errorMSG='<span class=txtNr>'.$l_moveWarn.'</span>';
echo ParseTpl(makeUp('main_warning'));
}

else{

if($topictitle!='' and $err1){
/* Create new topic here */

include($pathToFiles.'bb_func_txt.php');
$topictitle=str_replace(array('&#032;', '&#32;'), '', $topictitle);
$topic_title=textFilter($topictitle,$topic_max_length,$post_word_maxlength,0,1,0,$user_id,255);

$topic_poster=$rs[1];
$topic_poster_name=$rs[2];
$topic_time=$rs[3];
$topic_views=0;
$topic_status=0;
$topic_last_post_id=max($movedMsgs);
$posts_count=0;
$sticky=0;
$topic_last_post_time=$rs[3];

insertArray(array('topic_title', 'topic_poster', 'topic_poster_name', 'topic_time', 'topic_views', 'forum_id', 'topic_status', 'topic_last_post_id', 'posts_count', 'sticky', 'topic_last_post_time'), $Tt);
$topicid=$insres;
$userStats=TRUE;

}

if($rs2=db_simpleSelect(0,$Tt,'topic_id','topic_id','=',$topicid)){

/* Post and topic exist, now we move */

$currtop=$rs[0];

foreach($movedMsgs as $postid){

clearstatcache();

/* file upload addon handling */
if(file_exists($pathToFiles.'addon_fileupload_options.php')) include($pathToFiles.'addon_fileupload_options.php');
if(isset($dirsTopic) and $dirsTopic) {
$moveDir=$uploadDir.'/'.$currtop;
$moveDir2=$uploadDir.'/'.$topicid;
$filesFound=0;
$filesRenamed=0;
if(is_dir($moveDir)){
if ($handle = opendir($moveDir)) {
while (false !== ($file = readdir($handle))) {
if ($file != '.' and $file != '..') {
$filesFound++;
$rn=explode('_', $file);
if($rn[0]==$postid){
if(!is_dir($moveDir2)) {
umask(0);
if(!mkdir($moveDir2,0777)) die('Can not create separate topic directory.');
}
rename($moveDir.'/'.$file, $moveDir2.'/'.$file);
$filesRenamed++;
}
}
}
}
closedir($handle);
}
if($filesFound==$filesRenamed and is_dir($moveDir)) rmdir($moveDir);
}
/* --file upload addon handling */

}

$tot=0;
$fornew=db_simpleSelect(0,$Tt,'forum_id','topic_id','=',$topicid); $fornew=$fornew[0];
$forold=db_simpleSelect(0,$Tt,'forum_id','topic_id','=',$currtop); $forold=$forold[0];

$topic_id=$topicid;
$forum_id=$fornew;
foreach($movedMsgs as $postid) {
$tot+=updateArray(array('topic_id', 'forum_id'), $Tp, 'post_id', $postid);
}

/* Check if it was the only one post in the topic, delete the whole topic then! */
$oldCnt=db_simpleSelect(0,$Tp,'count(*)','topic_id','=',$currtop); $oldCnt=$oldCnt[0];
if($oldCnt==0) {
db_delete($Tt,'topic_id','=',$currtop);
db_calcAmount($Tt,'forum_id',$forold,$Tf,'posts_count');
$userStats=TRUE;
}
else {
/* Max stuff */

if($rs=db_simpleSelect(0,$Tp,'post_id, post_time, poster_name','topic_id','=',$currtop,$orderby='post_id DESC',1)){
$topic_last_post_id=$rs[0];
$topic_last_post_time=$rs[1];
$topic_last_poster=$rs[2];
$tot+=updateArray(array('topic_last_post_id', 'topic_last_post_time', 'topic_last_poster'),$Tt,'topic_id',$currtop);
}

}

if($rs=db_simpleSelect(0,$Tp,'post_id, post_time, poster_name','topic_id','=',$topicid,$orderby='post_id DESC',1)){
$topic_last_post_id=$rs[0];
$topic_last_post_time=$rs[1];
$topic_last_poster=$rs[2];
$tot+=updateArray(array('topic_last_post_id', 'topic_last_post_time', 'topic_last_poster'),$Tt,'topic_id',$topicid);
}

/* determine new page */
$minPost=min($movedMsgs);

if(isset($themeDesc) and in_array($topicid,$themeDesc)) $vv=TRUE; else $vv=FALSE;
if(!$vv) $sg='<='; else $sg='>=';
if($row=db_simpleSelect(0, $Tp, 'count(*)', 'post_id', $sg, $minPost, '', '', 'topic_id', '=', $topicid)) $pt=$row[0]; else $pt=0;
if( ($pt%$viewmaxreplys) == 0) $newPage=$pt/$viewmaxreplys;
else $newPage=(integer)($pt/$viewmaxreplys)+PAGE1_OFFSET+1;

db_calcAmount($Tp,'forum_id',$fornew,$Tf,'posts_count');
db_calcAmount($Tp,'forum_id',$forold,$Tf,'posts_count');

db_calcAmount($Tt,'forum_id',$fornew,$Tf,'topics_count');
db_calcAmount($Tt,'forum_id',$forold,$Tf,'topics_count');

db_calcAmount($Tp,'topic_id',$currtop,$Tt,'posts_count');
db_calcAmount($Tp,'topic_id',$topicid,$Tt,'posts_count');

if($userStats){
if(!defined('ARCHIVE')) $arcId=''; else $arcId=str_replace('-', '_', ARCHIVE);
db_calcTotalUserAmount($origPosterId, $Tu, $Tt, $Tp, $Taus, $arcId);
}

if(isset($mod_rewrite) and $mod_rewrite){
$urlp1=addTopicURLPage(genTopicURL($main_url, $fornew, '#GET#', $topicid, '#GET#'), $newPage)."#msg{$minPost}";
$urlp2=addTopicURLPage(genTopicURL($main_url, $forold, '#GET#', $currtop, '#GET#'), $oldPage);
}
else{
$urlp1=addGenURLPage("{$main_url}/{$indexphp}action=vthread&amp;topic={$topicid}&amp;forum={$fornew}", $newPage)."#msg{$minPost}";
$urlp2=addGenURLPage("{$main_url}/{$indexphp}action=vthread&amp;topic={$currtop}&amp;forum={$forold}", $oldPage);
}

$smd=sizeof($movedMsgs);

echo "<span class=\"txtNr\">{$l_movedOk} {$tot}<br /><a href=\"{$urlp1}\">{$l_viewMovedTopic}</a> / <a href=\"{$urlp2}\" target=\"_blank\">{$l_viewOldTopic}</a></span>";

if($smd>0){
echo "<script type=\"text/javascript\">window.opener.location = '".addslashes(operate_string($urlp2, TRUE))."';</script>";
}

}
else {
$errorMSG='<span class="txtNr">'.$l_moveWarn.'</span>';
echo ParseTpl(makeUp('main_warning'));
}

}//topic title is not set

}

else{
$post=(isset($_GET['post'])?(integer)$_GET['post']+0:'');
$forum=(isset($_GET['forum'])?(integer)$_GET['forum']+0:'0');

if(isset($_GET['forum'])) $forum=(integer)$_GET['forum']+0; elseif(isset($_POST['forum'])) $forum=(integer)$_POST['forum']+0; else $forum=0;

if(isset($_POST['deleteAll']) and is_array($_POST['deleteAll']) and sizeof($_POST['deleteAll'])>1){
//mass moving
$whatToMove='<span class="txtNr"><strong>'.$l_moveArray.' ['.sizeof($_POST['deleteAll']).']</strong></span><br />';
$whatToMove.='<input type="hidden" name="movedMsgs" value="'.implode(',', $_POST['deleteAll']).'" />';
}
else{
$whatToMove=<<<out
<span class="txtNr"><input type="text" name="postid" size="6" maxlength="10" class="textForm" value="{$post}" readonly="readonly" /><br />{$l_postId}<br /></span>
out;
}

$frm=0;
include($pathToFiles.'bb_func_forums.php');

$predefinedTopics='';
$pastePredefinedJs='';
if(isset($predefinedMovedTopics) and sizeof($predefinedMovedTopics)>0) {
$pastePredefinedJs=<<<out
<script type="text/javascript">
<!--
function pastePredefJs(topic){
document.forms['movepost'].elements['topicid'].value=topic;
return;
}
//-->
</script>
out;

$xtr=getClForums($predefinedMovedTopics, 'where', '', 'topic_id', 'or', '=');
if($row=db_simpleSelect(0, $Tt, 'topic_id, topic_title', '', '', '', 'posts_count desc')){
do{
$predefinedTopics.="<a href=\"javascript:pastePredefJs('{$row[0]}')\">{$row[1]}</a><br />";
}
while($row=db_simpleSelect(1));
}
unset($xtr);
}

echo <<<out
{$pastePredefinedJs}
<form action="{$indexphp}" method="post" name="movepost" id="movepost">
{$whatToMove}
<br />
<table class="tbTransparentmb">
<tr>
<td class="caption1" style="width:50%;vertical-align:top;padding:0px"><span class="txtNr"><input type="text" name="topicid" size="6" maxlength="10" class="textForm" /><br />{$l_topicId}<br /></span></td>
<td style="width:50%;vertical-align:top"><span class="txtNr">{$predefinedTopics}&nbsp;</span></td>
</tr>
</table>

<span class="txtNr"><input type="text" name="topictitle" size="38" maxlength="{$topic_max_length}" class="textForm" style="width:450px;" /><br />{$l_newTopicMove}
<br /><br />
<select name="forum_id" class="selectTxt">
{$listForums}
</select>
<br />{$l_moveForum}</span>
<br /><br />
<input type="hidden" name="action" value="movepost" />
<input type="hidden" name="forum" value="{$forum}" />
<input type="hidden" name="step" value="2" />
<input type="submit" value="{$l_movePost}" class="inputButton" />
</form>
out;
}


echo '</td></tr></table>';
}

?>