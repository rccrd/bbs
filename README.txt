=========================

miniBB compilation date: 2017-02-17 00:59:11

You are allowed to distribute copies of this program package accordingly to the Generic Public License terms.
Refer to the COPYING file for more information.

miniBB Compiler (c) Copyright 2010-2012 Paul Puzyrev www.minibb.com
=========================

This file lists alternative, quick installation instructions for miniBB, inherited from the miniBB compiler, http://www.minibb.com/compiler.html
Auto-compiler's task is to provide a QUICK package which is fully functional by default and doesn't need anymore specific adjustments.

! Please, skip the default installation instructions, which are described in miniBB Manual, "Installation Step #1" chapter. You only should follow instructions described below.


BASIC PACKAGE INSTALLATION INSTRUCTIONS

- Unpack the archive somewhere on your disk. Probably you already did it. Extract all files AS they are provided. Follow folders and files structure.

- Open setup_options.php for editing in the qualified text editor, which doesn't add extra spaces on both ends of the file, doesn't change its encoding and linespacing. Here and further, for editing plain scripts and files we recommend Notepad2, or similar simple program for such purpose.

-- On your server, you should have a mySQL database prepared. You should know this database's name, host, login and password to access. This data needs to be entered under the following settings: $DBhost, $DBname, $DBusr, $DBpwd.

-- Under $admin_usr setting, specify default username for the forums Root Admin account. Under $admin_pwd, specify the password. Under $admin_email, set Root's email address.

-- Under $main_url, set up the URL to your upcoming forums, like under which address they will appear on the web. It doesn't mean URL to your website! This setting is important in all aspects, because miniBB software, in default mode, can be run only from the specific domain's path. Such URL should not end with the slash, it's IMPORTANT.

* Above steps may be also completed, modifying files live on the server.

- After you are done with all configuration options, copy all program files to your server's folder, where you would like to run your forums. Choose a reliable FTP program for this task, the program which puts all files as they are, with no binary character changes. Our recommendation is Total Commander's FTP client. 

- After all files are properly copied, run `YOUR_FORUMS_URL/_install.php` from your browser. If you have configured everything probably, it should suggest miniBB installation screen with the quick report on security options of your server. Study your server's configuration and proceed to forums installation with one single click. Follow further instructions on the screen to login to your admin panel and create forums. You're done!




Depending on your tastes and forums specifics, miniBB could be modified and adjusted in many other aspects. Follow post-configuration instructions from:

http://www.minibb.com/forums/manual.html#config2

Besides of it, each add-on may have extra configuration options, found under the proper add'ons options file. Refer to the original add-on packages supplied amongst miniBB Downloads or Customers Area, and their README instructions for more details.

All compiled add-ons could be removed at later time, following installation instructions from the other end; as well they could be upgraded in the future separately.

Visit our Community to post your questions, or study hundreds of other ways to change miniBB and yourself:

http://www.minibb.com/forums/




SETTING FOLDERS AND FILES PERMISSIONS

Because of your compilation choice, some of the folders and/or files should have permissions available for writing. On Unix/Linux, you must give such permissions manually, entering forums folder by FTP or command shell and applying the command like "chmod 0777" for each reference. Many hosters do not require such operation (files and folders may be accessible automatically), however on the other hand this is related to the security leak. References list is as follows:

[shared_files]
[shared_files]/[avatars]

LIST OF COMPILED ADD-ONS

The list below just describes the miniBB add-ons which were built in your package. It has only informative purpose.
If you would like to read more about add-ons settings and options, refer to README files which come along their packages.

 - Unread Messages Indicator
 - Avatars
 - Moving Replies
 - Anti-Guest
 - Color Picker
 - Vimeo BB-code
 - YouTube BB-code
 - Bulleted List BB-code
 - Highlight BB-code
 - Spoiler BB-code
