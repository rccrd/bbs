<?php
/*
addon_avatar.php : avatars addon file for miniBB.
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty.
Check COPYING file for more details.
Copyright (C) 2004-2014, 2016 Paul Puzyrev. www.minibb.com
Latest File Update: 2016-May-18
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

include($pathToFiles.'addon_avatar_options.php');

if(isset($_GET['adminUser'])) $adminUser=(integer)$_GET['adminUser']+0; elseif(isset($_POST['adminUser'])) $adminUser=(integer)$_POST['adminUser']+0; else $adminUser=0;

$editedMod=FALSE;
if(isset($mods)){
foreach($mods as $k=>$v) if(in_array($adminUser,$v)) { $editedMod=TRUE; break; }
}

$tmpUser=$user_id;
if($adminUser>1 and ($user_id==1 or ($isMod==1 and $adminUser!=$user_id and !$editedMod) ) ) {
if(!defined('ADMIN_USER_TMP')) define('ADMIN_USER_TMP', $adminUser);
$user_id=$adminUser;
$adminUserLnk='&amp;adminUser='.$adminUser;
$adminUserLnkC='&adminUser='.$adminUser;
}
else {
$adminUserLnk=''; $adminUserLnkC=''; $adminUser=0;
}

if($user_id!=0){

if($user_id==1 and isset($_GET['deleteAvatarsDir'])) { rmdir($avatarDir); exit; }

//attempt to create/chmod avatars folder
/*
if($avatarMaxFileSize>0 and !is_dir($avatarDir)) {
umask(002);
mkdir($avatarDir,0777);
}
*/

if($avatarMaxFileSize>0){
if(!is_dir($avatarDir) or !is_writable($avatarDir)) die('Avatar plugin is set up to work with uploaded files, but the folder '.$avatarDir.' is not available to write or doesn\'t exist. Can not continue - please, check your settings and file permissions first!');
}

if($GLOBALS['enableProfileUpdate'] and $user_id>0){

$fName=$pathToFiles.'lang/avatars_'.$lang.'.php';
if(file_exists($fName)) include($fName); else include($pathToFiles.'lang/avatars_eng.php');

if($action=='prefs' or $action=='editprefs'){

$curr=db_simpleSelect(0,$Tu,$avatarDbField,$dbUserId,'=',$user_id);
$curr=$curr[0];

/* Displaying form */
$delLink=0;
if($curr!='' and strlen($curr)==3 and file_exists($avatarDir.'/'.$user_id.'.'.$curr)) {
$avatar="<img src=\"{$avatarUrl}/{$user_id}.{$curr}?".date('His')."\" alt=\"\">";
$delLink=1;
}
elseif($curr!='' and substr_count($curr, '.')>0){
$avatar="<img src=\"{$main_url}/img/forum_avatars/{$curr}\" alt=\"{$curr}\">";
$delLink=1;
}
else { $avatar=''; }

if($delLink==1) {
$csrfchk=$_COOKIE[$cookiename.'_csrfchk'];
$avatarDel="<a href=\"{$main_url}/{$indexphp}action=avatardelete&amp;csrfchk={$csrfchk}{$adminUserLnk}\">$l_deleteAvatar</a>";
} else $avatarDel='';

$aTpl=makeUp('addon_avatar_userform');
if($avatarMaxFileSize==0) $aTpl=preg_replace("#<!--avatar_upload_link-->(.+?)<!--/avatar_upload_link-->#is", '', $aTpl);
if($chooseTableCells==0) $aTpl=preg_replace("#<!--avatar_list_link-->(.+?)<!--/avatar_list_link-->#is", '', $aTpl);
$avatarForm=ParseTpl($aTpl);
}

/* upload avatar - step 1 */

elseif($action=='avatarupload1' and $avatarMaxFileSize>0){

$title.=$l_uploadAvatar;

$aTpl=makeUp('addon_avatar_upload');

$sizeKb=floor($avatarMaxFileSize/1024);

if($maxAvatarWidth>0 and $maxAvatarHeight>0) {
if($staticAvatarSize) $aTpl=preg_replace("#<!--VARY_SIZE-->(.+?)<!--/VARY_SIZE-->#is", '', $aTpl);
else $aTpl=preg_replace("#<!--STATIC_SIZE-->(.+?)<!--/STATIC_SIZE-->#is", '', $aTpl);
}

echo load_header();
echo ParseTpl($aTpl);
return;

}

/* upload avatar - step 2 */

elseif($action=='avatarupload2' and $avatarMaxFileSize>0){
$warn=0;

if(isset($_FILES['userfile']) and is_uploaded_file($_FILES['userfile']['tmp_name'])){

$fileName=strtolower($_FILES['userfile']['name']);
for($i=strlen($fileName)-1; $i>=0; $i--){
if($fileName[$i]=='.') break;
}
if($i>0) $ext=substr($fileName, $i+1, strlen($fileName)-1);
else $ext='';

$iWidth=0; $iHeight=0;

if(isset($avatarAvailableTypes[$_FILES['userfile']['type']])) $extension=$avatarAvailableTypes[$_FILES['userfile']['type']];
else $extension='';

if(function_exists('getimagesize')) {
$size=@getimagesize($_FILES['userfile']['tmp_name']);
$iWidth=(int)$size[0]; $iHeight=(int)$size[1];
//if($size['mime']!=$_FILES['userfile']['type']) $gotSize=FALSE; else $gotSize=TRUE;
$gotSize=TRUE;
}
else $gotSize=FALSE;

$malicious=scanFilePHP($_FILES['userfile']['tmp_name']);

if($extension=='' or $extension!=$ext or $_FILES['userfile']['size']>$avatarMaxFileSize or !$gotSize or ($gotSize and ($iWidth>$maxAvatarWidth or $iHeight>$maxAvatarHeight or $iWidth==0 or $iHeight==0)) or $malicious) {
$warn=1;
}
elseif($staticAvatarSize and $iWidth!=$maxAvatarWidth and $iHeight!=$maxAvatarHeight) {
$warn=1;
}
else {
/* Finally, we done all checkings - and mark avatar in user's info as uploaded.*/

umask(0);
if(move_uploaded_file($_FILES['userfile']['tmp_name'], "{$avatarDir}/{$user_id}.{$extension}")){
$$avatarDbField=$extension;
chmod("{$avatarDir}/{$user_id}.{$extension}", 0777);
updateArray(array($avatarDbField),$Tu,$dbUserId,$user_id);
}

}//preg

}//upl.
else $warn=1;

if($warn==1){
$errorMSG=$l_uploadError;
$correctErr="<a href=\"{$main_url}/{$indexphp}action=prefs{$adminUserLnk}\">{$l_editPrefs}</a>";
echo load_header();
echo ParseTpl(makeUp('main_warning'));
return;
}
else { header("Location: {$main_url}/{$indexphp}action=prefs{$adminUserLnkC}&avatar=1#avatar"); exit; }

}//upl2

/* delete avatar from info */

elseif($action=='avatardelete' and $csrfchk!='' and $csrfchk==$_COOKIE[$cookiename.'_csrfchk']){
$$avatarDbField='';
updateArray(array($avatarDbField),$Tu,$dbUserId,$user_id);
foreach($avatarAvailableTypes as $mime=>$ext) if(file_exists("{$avatarDir}/{$user_id}.{$ext}")) unlink("{$avatarDir}/{$user_id}.{$ext}");
header("Location: {$main_url}/{$indexphp}action=prefs{$adminUserLnkC}#avatar"); exit;
}

/* choose avatar from list - step 1 */

elseif($action=='avatarchoose1' and $chooseTableCells>0){
$title.=$l_chooseAvatar;

$avatarsList='<table class="tbTransparent" style="width:100%">';

if (is_dir($pathToFiles.'img/forum_avatars/') and $handle=opendir($pathToFiles.'img/forum_avatars/')) {
$a=1;
while(($file=readdir($handle))!=false) {
if($file!='.' and $file!='..' and (substr($file, -4)=='.gif' OR substr($file, -4)=='.jpg' OR substr($file, -5)=='.jpeg' OR substr($file, -4)=='.png')) {

if($a==1) $avatarsList.='<tr>';

$avatarsList.="<td style=\"text-align:center;vertical-align:top\"><a href=\"{$main_url}/{$indexphp}action=avatarchoose2&amp;avatar=".urldecode($file)."{$adminUserLnk}\"><img src=\"{$main_url}/img/forum_avatars/{$file}\" alt=\"{$l_chooseAvatar}\" /></a><br />{$file}</td>";

$a++;
if($a>$chooseTableCells) { $avatarsList.='</tr>'; $a=1; }

}
}
closedir($handle);
$a--;
if($a>1) {
for($i=$chooseTableCells; $i>$a; $i--) $avatarsList.='<td>&nbsp;</td>';
$avatarsList.='</tr>';
}
$avatarsList.='</table>';
}

echo load_header();
echo ParseTpl(makeUp('addon_avatar_choose'));
return;
}

elseif($action=='avatarchoose2' and $chooseTableCells>0){
$avFile=urldecode(str_replace(array('/','\\'), '', $_GET['avatar']));
if(file_exists($pathToFiles."img/forum_avatars/{$avFile}")) {
$$avatarDbField=$avFile;
updateArray(array($avatarDbField),$Tu,$dbUserId,$user_id);
foreach($avatarAvailableTypes as $mime=>$ext) if(file_exists("{$avatarDir}/{$user_id}.{$ext}")) unlink("{$avatarDir}/{$user_id}.{$ext}");
}
header("Location: {$main_url}/{$indexphp}action=prefs{$adminUserLnkC}&avatar=1#avatar"); exit;
}

elseif($action=='removeuser' and isset($_POST['step']) and $_POST['step']=='remove' and ($isMod==1 or $user_id==1) and $csrfchk!='' and $csrfchk==$_COOKIE[$cookiename.'_csrfchk']) {
$userID=(isset($_POST['user'])?(integer)$_POST['user']+0:0);
foreach($avatarAvailableTypes as $mime=>$ext) if(file_exists("{$avatarDir}/{$userID}.{$ext}")) unlink("{$avatarDir}/{$userID}.{$ext}");
}

elseif(defined('ADMIN_PANEL') and $action=='searchusers2' and isset($_POST['delus']) and is_array($_POST['delus']) and sizeof($_POST['delus'])>0) {

$delUsers=$_POST['delus'];

foreach($delUsers as $userID){
$userID=(int)$userID+0;
foreach($avatarAvailableTypes as $mime=>$ext) if(file_exists($avatarDir.'/'.$userID.'.'.$ext)) unlink($avatarDir.'/'.$userID.'.'.$ext);
}

}

elseif($action=='delAvatarAdmin' and ($user_id==1 or $isMod==1) and $csrfchk!='' and $csrfchk==$_COOKIE[$cookiename.'_csrfchk']) {
$userID=(integer)$_GET['user']+0;
$$avatarDbField='';
updateArray(array($avatarDbField),$Tu,$dbUserId,$userID);
foreach($avatarAvailableTypes as $mime=>$ext) if(file_exists("{$avatarDir}/{$userID}.{$ext}")) unlink("{$avatarDir}/{$userID}.{$ext}");
if($topic==0) $action='userinfo'; else $action='vthread';
}

elseif($action!='vthread' and $action!='userinfo'){
header("Location: {$main_url}/{$indexphp}action=prefs{$adminUserLnkC}#avatar"); exit;
}

}

if(defined('ADMIN_USER_TMP')) $user_id=$tmpUser;

}
/*
else die('Login failed. If you have changed your password, please re-login on the <a href="'.$main_url.'/'.$indexphp.'">main forums page.</a>');
*/

?>