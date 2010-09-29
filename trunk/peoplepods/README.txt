PeoplePods, version 0.667

PeoplePods is an SDK for creating modern web applications where many people
come together to meet, talk, share, read, work, publish and explore.  Every
effort has been made to NOT pigeon-hole you as a developer into one model of
a site over another.  PeoplePods is not a blog, it is not a social network,
and it is not a content management system.  Rather, PeoplePods is a set of tools
that can be combined in many ways to create all sorts of interactions, while
providing a simple and unified software interface.

For information about PeoplePods, in-depth documentation about the SDK, and
tools to help you run a successful site, visit PeoplePods.net!

http://peoplepods.net

PeoplePods is a product of XOXCO, Inc.  XOXCO is a clickable research and 
development company. We help people design and build web products and social 
applications.  We are available to help you customize PeoplePods for your needs!

http://xoxco.com

*************************************************
** REQUIREMENTS

PHP 5+
MySQL 4+
Level 9
10,000 HP


*************************************************
** LICENSE

PeoplePods is released under the MIT open source license.  

Please refer to LICENSE.txt

*************************************************
** PACKAGE CONTENTS

peoplepods/
	PeoplePods.php	- PHP include file	
	README.txt		- This file
	LICENSE.txt		- License information
	INSTALL.txt		- Installation instructions
	admin/ 			- PeoplePods Command Center
	files/			- Stub directories for cache and file uploads
	install/		- Installation script
	js/				- Javascript libraries
	lib/			- PeoplePods SDK files
	pods/			- Core Pods
	themes/			- Default themes
		default/	- Default front end theme
		admin/		- Default Command Center theme


*************************************************
** INSTALLING PEOPLEPODS

Please refer to INSTALL.txt or view online at:

http://peoplepods.net/readme/installing-peoplepods


*************************************************
** UPGRADING PEOPLEPODS

1) To be extra safe, first make a backup of your entire /peoplepods directory

2) Then, make a copy of peoplepods/lib/etc/options.php.  You will need this file
after you do the update.  Also, make sure to copy any custom pods and themes you've created,
as well as any pods or themes you've modified.

3) Download the latest version of PeoplePods from http://peoplepods.net/version

4) Upload the .tar.gz file to the directory that currently contains the peoplepods/ folder

5) Un-tar this file by running this command:

> tar -zxvf peoplepods-0.667.tar.gz

This will overwrite all of your existing PeoplePods files.  

6) Move the copy of peoplepods/lib/etc/options.php that you made back into peoplepods/lib/etc/

Voila!  


*************************************************
** RELEASE NOTES

Download the latest version of PeoplePods at http://peoplepods.net/version

v0.667
Nov 03, 2009

View these notes online: http://peoplepods.net/version/667

* Fixed bug in install where meta table was created with an invalid enum field
* Fixed bug in content and people admin tools where files uploaded were not handled properly
* Fixed bug in core_friends module where incorrect permission was being checked
* Fixed bug in core_usercontent that caused /edit to overlap with /editprofile
* Fixed bug where empty document returned false on ->success()


v 0.666
Oct 31, 2009
DEVELOPER PREVIEW

View these notes online: http://peoplepods.net/version/666

This is the first release of PeoplePods!  I've decided to call this a developer
preview because some of the SDK is still under-documented, and because there may
be rapid fixes rolling out over the first few weeks that I wouldn't want any
non-experts dealing with.  That said, thank you for downloading PeoplePods!

The intent of PeoplePods is not to provide an "off the shelf" social network.  The
core pods and default theme are provided as examples of the capabilities of the SDK,
and should not be used to run your final site.  While some of the pods can be used without
modification, you will definitely want to go into more depth than a simple CSS skin.

That said, they are absolutely intended to be used as the basis for your own work:
cut-and-paste to your hearts content! 

I recommend that you do not modify the Pod and Theme files that came with PeoplePods.
Instead, MAKE COPIES, and modify the copies.   This way, future releases will not
overwrite your changes.

Please send us feedback about your experience with PeoplePods on the forum at our website.


*************************************************
** COMING SOON

* News Feed/Activity streams
* Support for Memcached
* Support for Facebook Connect
* Support for OpenID
* Support for multiple file attachments in Command Center
* Support for flags in Command Center

Keep up with new releases and information at our blog:
http://peoplepods.net/news

*************************************************
** CONTACT US

Found a bug?  Got a patch?  Want to share the pod or theme you made?  
Visit our forum or send us an email.

http://peoplepods.net/forum
info@peoplepods.net

Need help using PeoplePods to build your site?  
XOXCO, Inc, the creators of PeoplePods, can help!

http://xoxco.com/
info@xoxco.com

