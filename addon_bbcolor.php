<?php
/*
addon_bbcolor.php : color picker add-on for miniBB 2.
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2007 Paul Puzyrev. www.minibb.net
Latest File Update: 2008-Mar-11
*/

/* Options */
$splitRow=0;
$colorsArray=array('000000', '610B38', 'DF0101', '8A4B08', 'FF8000', '0B610B', '01DF01', '01DFD7', '08088A', '2E2EFE', '7401DF', 'DF01D7', '585858', 'BDBDBD', 'D0A9F5', 'A9D0F5');
$sq=20;

if(!isset($l_bb_color)) $l_bb_color='Color Picker';

/* Code */
if (!defined('INCLUDED776')) die ('Fatal error.');

$tbWidth=$sq*$splitRow;
if($splitRow>0) $tbHeight=ceil(sizeof($colorsArray)/$splitRow); else $tbHeight=$tbWidth;

$colorPicker='';

$colorPicker.=<<<out
<table class="forums" style="width:{$tbWidth}px;height:{$tbHeight}px;margin-right:0px;margin-left:0px;">
out;

$s=1;

foreach($colorsArray as $val) {

if($s==1) {
$colorPicker.='<tr>';
$cltr=FALSE;
}

$colorPicker.=<<<out
<td style="background-color:#{$val};width:{$sq}px;height:{$sq}px">
<a href="JavaScript:paste_strinL(selektion,3,'[font#'+'{$val}'+']','[/font]','');" onmouseover="window.status='#{$val}';return true;" onmouseout="window.status='';return true;" onmousemove="pasteSel()"><img src="{$main_url}/img/p.gif" alt="#{$val}" title="#{$val}" style="width:{$sq}px;height:{$sq}px;cursor:crosshair;border:0px;background-color:#{$val}" /></a></td>
out;

$s++;

if($splitRow>0 and $s>$splitRow) {
$s=1;
$colorPicker.='</tr>';
$cltr=TRUE;
}

}

if($s<=$splitRow and $s>1){
$csp=$splitRow-$s+1;
$colorPicker.="<td colspan=\"{$csp}\"><img src=\"{$main_url}/img/p.gif\" alt=\"\" title=\"\" style=\"width:{$sq}px;height:{$sq}px;\" /></td>";
}

if(!$cltr) $colorPicker.='</tr>';

$colorPicker.='</table>';

?>