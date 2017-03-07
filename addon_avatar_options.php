<?php

/* Avatar add-on settings */

$avatarDir=$pathToFiles.'shared_files/avatars'; //general path to your directory, where "avatars" directory will be CREATED and used. On Linux/FreeBSD/Unix systems, it must have 0777 privileges. Many miniBB plugins will use this directory, so if you already have one, it is better to keep one directory for all shared files. No slash at the end!

$avatarUrl="{$main_url}/shared_files/avatars"; //general WWW path to avatars images. No slash at the end.

$avatarMaxFileSize=65536; //maximum avatar file size in Bytes (10240 = 10 Kbytes). If you want to DISABLE uploads, set this to 0.

$maxAvatarWidth=80; //maximum picture width in pixels. Set amount of maximum width and height ONLY if you have GD library installed, ELSE set them to 0 (any pic width and height will be allowed, just because it is impossible to determine w/h then).
$maxAvatarHeight=80; //maximum picture height in pixels. 

$staticAvatarSize=FALSE;// set to TRUE if you would like to allow to upload static size avatars ONLY (the size defined under $maxAvatarWidth and $maxAvatarHeight). This is the most suitable way if you're using threads layout model where username row is placed on top of a message text. Setting this to FALSE allows to upload various size avatars which fits under dimensions specified in $maxAvatarWidth and $maxAvatarHeight.

$avatarAvailableTypes=array(
'image/pjpeg'=>'jpg',
'image/jpeg'=>'jpg',
'image/x-png'=>'png',
'image/png'=>'png',
'image/gif'=>'gif'
); //the list of available picture mime-types and their extensions. Upon uploading, some of these types will be saved with the provided extension. If a wrong file type uploaded, the script will not allow it. Do not set 'bmp' or 'tif' here, please - they are too big to fit into the avatar's size.

$avatarDbField='user_custom1'; // name of the field in user's table. By default, we will use the first from custom miniBB fields.

if(!defined('MOBILE')) $chooseTableCells=0; else $chooseTableCells=0; //amount of cells in 'existing' avatars list; differs for default and mobile version. If you don't want to use existing/prepared avatars, set this to 0.

?>