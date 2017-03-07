<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details.
Copyright (C) 2016 Paul Puzyrev. www.minibb.com
Latest File Update: 2016-Jun-19
*/

$GLOBALS['imgsWidth']=150; //static width for shrinking images

$simpleCodes=array(
'<img src',
'<embed src',
'<script type',
'<iframe',
);

if(isset($site_url)) $tUrl=$site_url;
else{
$t=explode('/', $main_url);
$tUrl=implode('/', array($t[0], $t[1], $t[2]));
}

function encodeList($matches){
$preg=preg_replace("#[\r\n]+#", '</li><li>', trim($matches[1]));
return '<ul class="limbb"><li>'.$preg.'</li></ul><br />';
}

function enCodeBB($msg,$admin) {

$pattern=array(); $replacement=array();

$userUrlsAllowed=($GLOBALS['allowHyperlinks']==0 or $GLOBALS['user_id']==1 or ($GLOBALS['user_id']>1 and isset($GLOBALS['user_num_posts']) and $GLOBALS['user_num_posts']>=$GLOBALS['allowHyperlinks']));

$dotsSiteUrl=str_replace('.', '\\.', $GLOBALS['tUrl']);

$pattern[]="/\[nourl\](.+?)\[\/nourl\]/i";
$replacement[]="<!-- nourl -->\\1<!-- /nourl -->";

/* Always allow URLs to the forums/website domain itself (internal forum links) */

$pattern[]='#\[url[=]?\]('.$dotsSiteUrl.'[^<> \n\r\[\]]*)\[/url\]#i';
$replacement[]='<a href="\\1" target="_blank">\\1</a>';

$pattern[]='#\[url=('.$dotsSiteUrl.'[^<> \n\r\[\]]*)\]\[/url\]#i';
$replacement[]='<a href="\\1" target="_blank">\\1</a>';

$pattern[]='#\[url=('.$dotsSiteUrl.'[^<> \n\r\[\]]*)\](.+?)\[/url\]#i';
$replacement[]='<a href="\\1" target="_blank">\\2</a>';

if($userUrlsAllowed){

$pattern[]="/\[url[=]?\]([^<> \n\r\[\]]+?)\[\/url\]/i";
$replacement[]="<a href=\"\\1\" target=\"_blank\"{$GLOBALS['relFollowUrl']}>\\1</a>";

$pattern[]="/\[url=((f|ht)tp[s]?:\/\/[^<> \n\r\[\]]+?)\]\[\/url\]/i";
$replacement[]="<a href=\"\\1\" target=\"_blank\"{$GLOBALS['relFollowUrl']}>\\1</a>";

$pattern[]="/\[url=((f|ht)tp[s]?:\/\/[^<> \n\r\[\]]+?)\](.+?)\[\/url\]/i";
$replacement[]="<a href=\"\\1\" target=\"_blank\"{$GLOBALS['relFollowUrl']}>\\3</a>";

}

/* Empty URLs fix */
$pattern[]="#<a href=\"([^<> \n\r\[\]]+)\"(.+?)>([ ]*?)</a>#i";
$replacement[]='';

/* local images - allowed for everybody */

/* fixed width */
$pattern[]='#\[imgs\]('.$dotsSiteUrl.'[^<> \n\r\[\]&\#]+?)\.(gif|jpg|jpeg|png)\[\/imgs\]#i';
$replacement[]='<a href="\\1.\\2" target="_blank"> <img src="\\1.\\2" alt="" title="" style="width:'.$GLOBALS['imgsWidth'].'px" /></a>';

/* fixed width and ALT */
$pattern[]='#\[imgs=('.$dotsSiteUrl.'[^<> \n\r\[\]&\#]+?)\.(gif|jpg|jpeg|png)\]([^<>\n\r\[\]&=/"\']+?)\[/imgs\]#i';
$replacement[]='<a href="\\1.\\2" target="_blank"> <img src="\\1.\\2" alt="\\3" title="\\3" style="width:'.$GLOBALS['imgsWidth'].'px" /></a>';

/* Non-declared code - without fixed width, with mandatory alt */
$pattern[]='#\[img=('.$dotsSiteUrl.'[^<> \n\r\[\]&\#]+?)\.(gif|jpg|jpeg|png)\]([^<>\n\r\[\]&=/"\']+?)\[/img\]#i';
$replacement[]='<img src="\\1.\\2" alt="\\3" title="\\3" />';

/* without fixed width and alt - f.e. local smileys */
$pattern[]='#\[img\]('.$dotsSiteUrl.'[^<> \n\r\[\]&\#]+?)\.(gif|jpg|jpeg|png)\[/img\]#i';
$replacement[]='<img src="\\1.\\2" alt="" title="" />';

/* external images - only allowed the proper extensions and codes by permission */

if($userUrlsAllowed){

/* fixed width and ALT */
$pattern[]="/\[imgs=(http[s]*:\/\/([^<> \n\r\[\]&\#]+?)\.(gif|jpg|jpeg|png))\]([^<>\n\r\[\]&=\/\"']+?)\[\/imgs\]/i";
$replacement[]='<a href="\\1" target="_blank"'.$GLOBALS['relFollowUrl'].'> <img src="\\1" alt="\\4" title="\\4" style="width:'.$GLOBALS['imgsWidth'].'px" /></a>';

/* fixed width */
$pattern[]="/\[imgs\](http[s]*:\/\/([^<> \n\r\[\]&\#]+?)\.(gif|jpg|jpeg|png))\[\/imgs\]/i";
$replacement[]='<a href="\\1" target="_blank"'.$GLOBALS['relFollowUrl'].'> <img src="\\1" alt="" title="" style="width:'.$GLOBALS['imgsWidth'].'px" /></a>';

/* Non-declared code - without fixed width, with alt - external images */
$pattern[]="/\[img=(http[s]*:\/\/([^<> \n\r\[\]&\#]+?)\.(gif|jpg|jpeg|png))\]([^<>\n\r\[\]&=\/\"']+?)\[\/img\]/i";
$replacement[]='<img src="\\1" alt="\\4" title="\\4" />';

/* Custom code - without fixed width, with alt - external images */
$pattern[]="/\[img\](http[s]*:\/\/[^<> \n\r\[\]&\#]+?\.(gif|jpg|jpeg|png))\[\/img\]/i";
$replacement[]='<img src="\\1" alt="" title="" />';

}

$pattern[]="/\[[bB]\](.+?)\[\/[bB]\]/s";
$replacement[]='<strong>\\1</strong>';

$pattern[]="/\[[iI]\](.+?)\[\/[iI]\]/s";
$replacement[]='<em>\\1</em>';

$pattern[]="/\[align(left|right|center)](.+?)\[\/align\]/is";
$replacement[]='<div style=\"text-align:\\1\">\\2</div>';

if($admin==1 or $GLOBALS['isMod']==1){

$pattern[]="/\[[uU]\](.+?)\[\/[uU]\]/s";
$replacement[]='<u>\\1</u>';

$pattern[]="/\[urlc=((f|ht)tp[s]?:\/\/[^<> \n\r\[\]]+?)\](.*?)\[\/url\]/i";
$replacement[]="<a href=\"\\1\" target=\"_blank\">\\3</a>";


}

$pattern[] = "/\[quote=(.+?)\][\r\n]*(.+?)\[\/quote\][\r\n]*/is";
$replacement[] = '<div class="quote"><div class="quoting">\\1: </div>\\2</div><br />';

$pattern[] = "/\[quote\][\r\n]*(.+?)\[\/quote\][\r\n]*/is";
$replacement[] = '<div class="quote">\\1</div><br />';

$pattern[] = "/\[hl\][\r\n]*(.+?)\[\/hl\][\r\n]*/is";
$replacement[] = '<div class="hl">\\1</div><br />';

$pattern[]="/\[font(#[A-F0-9]{6})\](.+?)\[\/font\]/is";
$replacement[]='<span style="color:\1">\2</span>';

/* Vimeo code */
$pattern[]="/\[vimeo=null\]/i";
$replacement[]='';

//default size
$pattern[]="/\[vimeo=http:\/(\/www\.|\/)vimeo\.com\/([0-9]+)\]/i";
$replacement[]="<iframe src=\"//player.vimeo.com/video/\\2\" width=\"400\" height=\"225\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";

//with size
$pattern[]="/\[vimeo([1-7][0-9][0-9])x([1-5][0-9][0-9])=http:\/(\/www\.|\/)vimeo\.com\/([0-9]+)\]/i";
$replacement[]="<iframe src=\"//player.vimeo.com/video/\\4\" width=\"\\1\" height=\"\\2\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";

//straight HTML iframe
$pattern[]="/&lt;iframe src=&quot;\/\/player\.vimeo\.com\/video\/([0-9]+)(.*)&quot; width=&quot;([1-7][0-9][0-9])&quot; height=&quot;([1-5][0-9][0-9])&quot; frameborder=&quot;0&quot; webkitAllowFullScreen mozallowfullscreen allowfullscreen&gt;&lt;\/iframe&gt;/i";
$replacement[]="<iframe src=\"//player.vimeo.com/video/\\1\" width=\"\\3\" height=\"\\4\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
/* --Vimeo code */

/* YouTube code */
$pattern[]="/\[youtube=null\]/i";
$replacement[]='';

$pattern[]="/\[youtube=http[s]*:\/(\/www\.|\/[a-z]+\.|\/)youtube\.com\/watch\?v=([a-zA-Z0-9-_]+)(.*)\]/i";
$replacement[]="<iframe width=\"425\" height=\"344\" src=\"https://www.youtube.com/embed/\\2\" frameborder=\"0\" allowfullscreen></iframe>";

$pattern[]="/\[youtube=http[s]*:\/(\/www\.|\/[a-z]+\.|\/)youtu\.be\/([a-zA-Z0-9-_]+)(.*)\]/i";
$replacement[]="<iframe width=\"425\" height=\"344\" src=\"https://www.youtube.com/embed/\\2\" frameborder=\"0\" allowfullscreen></iframe>";

//with size
$pattern[]="/\[youtube([1-7][0-9][0-9])x([1-5][0-9][0-9])=http[s]*:\/(\/www\.|\/[a-z]+\.|\/)youtu\.be\/([a-zA-Z0-9-_]+)(.*)\]/i";
$replacement[]="<iframe width=\"\\1\" height=\"\\2\" src=\"https://www.youtube.com/embed/\\4\" frameborder=\"0\" allowfullscreen></iframe>";

//straight HTML iframe
$pattern[]="/&lt;iframe width=&quot;([1-7][0-9][0-9])&quot; height=&quot;([1-5][0-9][0-9])&quot; src=&quot;https:\/\/www\.youtube\.com\/embed\/([a-zA-Z0-9-_]+)[?]*(.*?)&quot; frameborder=&quot;0&quot; allowfullscreen&gt;&lt;\/iframe&gt;/i";
$replacement[]="<iframe width=\"\\1\" height=\"\\2\" src=\"https://www.youtube.com/embed/\\3\" frameborder=\"0\" allowfullscreen></iframe>";
/* --YouTube code */

/* Spoiler */
$id=date('YmdHis').rand(100,999);
$pattern[] = "/\[spoiler\][\r\n]*(.+?)\[\/spoiler\][\r\n]*/is";
$replacement[] = '<div class="spoiler" id="spoiler'.$id.'" onclick="javascript:unspoil(this);"><div class="spoilerWarning">'.$GLOBALS['l_bb_spoiler'].'</div>\1</div><br />';
/* --Spoiler */

$msg=preg_replace($pattern, $replacement, $msg);
/* List BB code */
$msg=preg_replace_callback("/\[list\][\r\n]+(.+?)[\r\n]+\[\/list\][\r\n]*/is", 'encodeList', $msg);
/* --List BB code */

if(substr_count($msg,'<img')>0) $msg=str_replace('align=""', '', $msg);
if(substr_count($msg,'"nofollow"></a>')>0) $msg=str_replace('"nofollow"></a>', '"nofollow">URL</a>', $msg);
if(substr_count($msg,'"_blank"></a>')>0) $msg=str_replace('"_blank"></a>', '"nofollow">URL</a>', $msg);

return $msg;
}

//--------------->
function deCodeBB($msg) {

$pattern=array(); $replacement=array();

$pattern[]="/<!-- nourl -->([^<>\n\r]+?)<!-- \/nourl -->/i";
$replacement[]="[nourl]\\1[/nourl]";

/* Old [IMG] tag code - without fixed width. */
$pattern[]="/<img src=\"([^<> \n\r\[\]&]+?)\" alt=\"\" (title=\"\" )?\/>/i";
$replacement[]="[img]\\1[/img]";

/* New [IMGs] tag code - with fixed width */ 
$pattern[]="/<a href=\"([^<> \n\r\[\]&]+?)\" target=\"_blank\"(".addslashes($GLOBALS['relFollowUrl']).")?>[ ]<img src=\"([^<> \n\r\[\]]+?)\" alt=\"\" (title=\"\" )?style=\"width:[0-9]+px\" \/><\/a>/i";
$replacement[]="[imgs]\\3[/imgs]";

/* [IMGS] tag code - with fixed width and alt */ 
$pattern[]="/<a href=\"([^<> \n\r\[\]&]+?)\" target=\"_blank\"(".addslashes($GLOBALS['relFollowUrl']).")?>[ ]<img src=\"([^<> \n\r\[\]]+?)\" alt=\"(.+?)\" (title=\"(.+?)\" )?style=\"width:[0-9]+px\" \/><\/a>/i";
$replacement[]="[imgs=\\3]\\4[/imgs]";

/* [IMG] tag code - without fixed width, with alt. */
$pattern[]="/<img src=\"([^<> \n\r\[\]&]+?)\" alt=\"(.+?)\" (title=\"(.+?)\" )?\/>/i";
$replacement[]="[img=\\1]\\2[/img]";

$pattern[]="/<a href=\"([^<> \n\r\[\]]+?)\" target=\"(_new|_blank)\"".addslashes($GLOBALS['relFollowUrl']).">(.+?)<\/a>/i";
$replacement[]="[url=\\1]\\3[/url]";

if($GLOBALS['user_id']==1 or (isset($GLOBALS['isMod']) and $GLOBALS['isMod']==1)){
$pattern[]="/<a href=\"([^<> \n\r\[\]]+?)\" target=\"(_new|_blank)\">(.+?)<\/a>/i";
$replacement[]="[urlc=\\1]\\3[/url]";
}
else{
$pattern[]="/<a href=\"([^<> \n\r\[\]]+?)\" target=\"(_new|_blank)\">(.+?)<\/a>/i";
$replacement[]="[url=\\1]\\3[/url]";
}

$pattern[]="/<strong>(.+?)<\/strong>/is";
$replacement[]="[b]\\1[/b]";

$pattern[]="/<em>(.+?)<\/em>/is";
$replacement[]="[i]\\1[/i]";

$pattern[]="/<[uU]>(.+?)<\/[uU]>/s";
$replacement[]="[u]\\1[/u]";

$pattern[]="/<span style=\"color:(#[A-F0-9]{6})\">(.+?)<\/span>/is";
$replacement[]='[font\\1]\\2[/font]';

$pattern[]="/<div style=\"text-align:(left|right|center)\">(.+?)<\/div>/is";
$replacement[]='[align\\1]\\2[/align]';

$pattern[] = '/<div class=\"quote\"><div class=\"quoting\">(.+?): <\/div>(.+?)<\/div>/is';
$replacement[] = "[quote=\\1]\\2[/quote]\n";

$pattern[] = '/<div class=\"quote\">(.+?)<\/div>/is';
$replacement[] = "[quote]\\1[/quote]\n";

$pattern[] = '/<div class=\"hl\">(.+?)<\/div>/is';
$replacement[] = "[hl]\\1[/hl]\n";

/* Vimeo code */
$pattern[]="/<iframe src=\"\/\/player\.vimeo\.com\/video\/([0-9]+)\" width=\"([0-9]+)\" height=\"([0-9]+)\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen><\/iframe>/i";
$replacement[]="[vimeo\\2x\\3=http://vimeo.com/\\1]";
/* --Vimeo code */

/* YouTube code */
$pattern[]="/<object width=\"[0-9]+\" height=\"[0-9]+\"><param name=\"movie\" value=\"http[s]*:\/\/www\.youtube\.com\/v\/([a-zA-Z0-9-_]+)&fs=1\"><\/param><param name=\"wmode\" value=\"transparent\"><\/param><param name=\"allowFullScreen\" value=\"true\"><\/param><embed src=\"http:\/\/www\.youtube\.com\/v\/([a-zA-Z0-9-_]+)&fs=1\" type=\"application\/x-shockwave-flash\" wmode=\"transparent\" width=\"[0-9]+\" height=\"[0-9]+\" allowfullscreen=\"true\"><\/embed><\/object>/i";
$replacement[]="[youtube=https://www.youtube.com/watch?v=\\1]";

$pattern[]="/<iframe width=\"([0-9]+)\" height=\"([0-9]+)\" src=\"[htps:]*\/\/www\.youtube\.com\/embed\/([a-zA-Z0-9-_]+)\" frameborder=\"0\" allowfullscreen><\/iframe>/";
$replacement[]="[youtube\\1x\\2=https://youtu.be/\\3]";
/* --YouTube code */

/* List BB code */
$pattern[] = '/<ul class="limbb">(.+?)<\/ul>/is';
$replacement[] = "[list]\r\n\\1[/list]\n";

$pattern[] = '/<li>(.+?)<\/li>/is';
$replacement[] = "\\1\r\n";

/* --List BB code */

/* Spoiler */
$pattern[] = '/<div class=\"spoiler\" id=\"spoiler([0-9]+)\"(.+?)><div class=\"spoilerWarning\">(.+?)<\/div>(.+?)<\/div>/is';
$replacement[] = "[spoiler]\n\4[/spoiler]\n";
/* --Spoiler */

$msg=preg_replace($pattern, $replacement, $msg);
$msg=str_replace ('<br />', "\n", $msg);
if(substr_count($msg, '[img\\2]')>0) $msg=str_replace('[img\\2]', '[img]', $msg);

if(function_exists('smileThis')) $msg=smileThis(FALSE,TRUE,$msg);

return $msg;
}

?>